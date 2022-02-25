<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Database\Database;
use App\Core\Log\Logger;
use App\Core\Patterns;
use App\Core\Settings;
use App\Repository\PatternRepository;
use App\Repository\ValidPatternRepository;
use App\Repository\WordRepository;
use Exception;
use Hyphenation\Algorithm\HyphenationTrie;
use PDOException;

class Hyphenation implements HyphenationInterface
{
    public function __construct(
        private HyphenationTrie $hyphenator,
        private WordRepository $wordRepository,
        private PatternRepository $patternRepository,
        private ValidPatternRepository $validPatternRepository,
        private Patterns $patterns,
        private Settings $settings,
        private Database $database,
        private Logger $logger
    ) {
        $this->hyphenator->formPatternTrie(
            $this->patterns->getPatterns()
        );
    }

    /**
     * @throws Exception
     */
    public function hyphenate(string $word): string
    {
        if($this->settings->getDatabaseUsageStatus()) {
            $wordModel = $this->wordRepository->getWordByName($word);
            if($wordModel){
                return $wordModel->getHyphenatedWord();
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
        if($this->settings->getDatabaseUsageStatus()) {
            $this->wordRepository->submitWord($word, $hyphenatedWord);
            $this->insertValidPatterns($validPatterns, $word);
        }
    }

    /**
     * @throws Exception
     */
    private function insertValidPatterns(array $validPatterns, string $word): void
    {
        $word = $this->wordRepository->getWordByName($word);
        $this->logger->info(sprintf('Word to hyphenate: %s', $word->getWord()));
        foreach($validPatterns as $pattern)
        {
            $this->logger->info(sprintf('Detected pattern : %s', $pattern));
            try{
                $this->database->getConnector()->beginTransaction();
                $pattern = $this->patternRepository->getPatternByName($pattern);
                $this->validPatternRepository->submitValidPattern($word->getId(), $pattern->getId());
                $this->database->getConnector()->commit();
            }catch(PDOException $e)
            {
                $this->database->getConnector()->rollBack();
            }
        }
    }
}

