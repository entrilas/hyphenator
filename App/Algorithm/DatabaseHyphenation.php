<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Log\Logger;
use App\Core\Settings;
use Exception;
use PDOException;

class DatabaseHyphenation implements HyphenationInterface
{
    public function __construct(
        private HyphenationInterface $hyphenator,
        private QueryBuilder $queryBuilder,
        private Settings $settings,
        private Database $database,
        private Logger $logger
    ) {
    }

    public function hyphenate(string $word): string
    {
        $wordValue = $this->getWord($word);
        $wordValue == "false" ? $isWordFound = false : $isWordFound = true;

        if($this->settings->isDatabaseValid() && !$isWordFound) {
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $validPatterns = $this->hyphenator->getValidPatterns();
            $this->insertWord($word, $hyphenatedWord, $validPatterns);
            return $hyphenatedWord;
        }elseif($this->settings->isDatabaseValid() && $isWordFound) {
            return $this->getHyphenatedWordName($word);
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
        if($this->settings->isDatabaseValid())
        {
            $this->queryBuilder->insert(
                'words',
                [$word, $hyphenatedWord],
                ['word', 'hyphenated_word']
            );
            $this->insertValidPatterns($validPatterns, $word);
        }
    }

    /**
     * @throws Exception
     */
    private function insertValidPatterns(array $validPatterns, string $word): void
    {
        $wordID = $this->getDataID($this->getWord($word));
        $this->logger->info(sprintf('Word to hyphenate: %s', $word));
        foreach($validPatterns as $pattern)
        {
            $this->logger->info(sprintf('Detected pattern : %s', $pattern));
            try{
                $this->database->getConnector()->beginTransaction();
                $patternID = $this->getDataID($this->getPattern($pattern));
                $this->queryBuilder->insert(
                    'valid_patterns',
                    [$wordID, $patternID],
                    ['fk_word_id', 'fk_pattern_id']
                );
                $this->database->getConnector()->commit();
            }catch(PDOException $e)
            {
                $this->database->getConnector()->rollBack();
            }
        }
    }

    private function getDataID($data): string
    {
        $decodedData = json_decode($data, true);
        return $decodedData['id'];
    }

    private function getHyphenatedWordName(string $word): string
    {
        $wordData = $this->getWord($word);
        $decodedData = json_decode($wordData, true);
        return $decodedData['hyphenated_word'];
    }

    private function getWord(string $word): bool|string
    {
        return $this->queryBuilder->select(
            'words',
            ['id', 'hyphenated_word'],
            'word',
            $word
        );
    }

    private function getPattern(string $pattern): bool|string
    {
        return $this->queryBuilder->select(
            'patterns',
            ['id'],
            'pattern',
            $pattern
        );
    }

}