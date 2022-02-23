<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Database\Database;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Log\Logger;
use App\Core\Patterns;
use App\Core\Settings;
use App\Models\Pattern;
use App\Models\ValidPattern;
use App\Models\Word;
use Exception;
use Hyphenation\Algorithm\HyphenationTrie;
use PDOException;

class Hyphenation implements HyphenationInterface
{
    private array $applicationSettings;

    public function __construct(
        private HyphenationTrie $hyphenator,
        private Word $word,
        private Pattern $pattern,
        private ValidPattern $validPattern,
        private Patterns $patterns,
        private Settings $settings,
        private Database $database,
        private Logger $logger
    ) {
        $this->applicationSettings = $this->settings->getConfig();
        $this->hyphenator->formPatternTrie(
            $this->patterns->getPatterns()
        );
    }

    /**
     * @throws Exception
     */
    public function hyphenate(string $word): string
    {
        if($this->applicationSettings['USE_DATABASE']) {
            if($this->word->getWordByName($word)){
                return $this->getHyphenatedWordName($word);
            }
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $validPatterns = $this->hyphenator->getValidPatterns();
            $this->insertWord($word, $hyphenatedWord, $validPatterns);
            return $hyphenatedWord;
        }
        return $this->hyphenator->hyphenate($word);
    }

    public function getValidPatterns(): array
    {
        return $this->hyphenator->getValidPatterns();
    }

    /**
     * @throws Exception
     */
    private function insertWord(
        string $word,
        string $hyphenatedWord,
        array $validPatterns)
    : void {
        if($this->applicationSettings['USE_DATABASE'])
        {
            $this->word->submitWord(
                ['word' => $word,
                'hyphenated_word' => $hyphenatedWord
                ]);
            $this->insertValidPatterns($validPatterns, $word);
        }
    }

    /**
     * @throws Exception
     */
    private function insertValidPatterns(array $validPatterns, string $word): void
    {
        $word = $this->word->getWordByName($word);
        $wordID = $word['id'];
        $this->logger->info(sprintf('Word to hyphenate: %s', $word));
        foreach($validPatterns as $pattern)
        {
            $this->logger->info(sprintf('Detected pattern : %s', $pattern));
            try{
                $this->database->getConnector()->beginTransaction();
                $pattern = $this->pattern->getPatternByName($pattern);
                $patternID = $pattern['id'];
                $this->validPattern->submitValidPattern(
                    ['fk_word_id' => $wordID,
                     'fk_pattern_id' => $patternID]
                );
                $this->database->getConnector()->commit();
            }catch(PDOException $e)
            {
                $this->database->getConnector()->rollBack();
            }
        }
    }

    private function getHyphenatedWordName(string $wordName): string
    {
        $hyphenatedWord = $this->word->getWordByName($wordName);
        return $hyphenatedWord['hyphenated_word'];
    }
}
