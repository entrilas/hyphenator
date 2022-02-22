<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Database\Database;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Log\Logger;
use App\Core\Settings;
use App\Models\Pattern;
use App\Models\ValidPattern;
use App\Models\Word;
use Exception;
use Hyphenation\Algorithm\HyphenationTrie;
use PDOException;

class Hyphenation implements HyphenationInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private HyphenationTrie $hyphenator,
        private Word $word,
        private Pattern $pattern,
        private ValidPattern $validPattern,
        private Settings $settings,
        private Database $database,
        private Logger $logger
    ) {
        $this->hyphenator->formPatternTrie(
            $this->settings->getPatterns()
        );
    }

    /**
     * @throws Exception
     */
    public function hyphenate(string $word): string
    {
        if($this->settings->isDatabaseValid()) {
            if($this->checkIfWordExists($word)){
                return $this->getHyphenatedWordName($word);
            }
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $validPatterns = $this->hyphenator->getValidPatterns();
            $this->insertWord($word, $hyphenatedWord, $validPatterns);
            return $hyphenatedWord;
        }
        return $this->hyphenator->hyphenate($word);
    }

    private function checkIfWordExists(string $word): bool
    {
        $wordValue = $this->word->getWordByName($word);
        $wordValue == "false" ? $isWordFound = false : $isWordFound = true;
        return $isWordFound;
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
        if($this->settings->isDatabaseValid())
        {
            $this->word->submitWord(
                ['word' => $word,
                'hyphenated_word' => $hyphenatedWord]
            );
            $this->insertValidPatterns($validPatterns, $word);
        }
    }

    /**
     * @throws Exception
     */
    private function insertValidPatterns(array $validPatterns, string $word): void
    {
        $wordID = $this->getDataID($this->word->getWordByName($word));
        $this->logger->info(sprintf('Word to hyphenate: %s', $word));
        foreach($validPatterns as $pattern)
        {
            $this->logger->info(sprintf('Detected pattern : %s', $pattern));
            try{
                $this->database->getConnector()->beginTransaction();
                $patternID = $this->getDataID($this->pattern->getPatternByName($pattern));
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

    private function getDataID($data): int
    {
        $decodedData = json_decode($data, true);
        return $decodedData['id'];
    }

    private function getHyphenatedWordName(string $word): string
    {
        $wordData = $this->word->getWordByName($word);
        $decodedData = json_decode($wordData, true);
        return $decodedData['hyphenated_word'];
    }
}
