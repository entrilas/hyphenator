<?php

namespace App\Repository;

use App\Core\Connection;
use App\Core\Database\QueryBuilder;
use App\Models\Word;
use App\Repository\Interfaces\IWordRepository;

class WordRepository extends Connection implements IWordRepository
{
    private string $table = 'words';

    public function __construct(QueryBuilder $queryBuilder
    ) {
        parent::__construct($queryBuilder);
    }

    public function getWords(): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->orderby(['id'])
            ->execute()
            ->getAllData();
    }

    public function getWord(int $id): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
        return $this->formWordModel($wordsArray);
    }

    public function getWordByName(string $word): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('word')
            ->execute([$word])
            ->getData();
        return $this->formWordModel($wordsArray);
    }

    public function submitWord(string $word, string $hyphenatedWord): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['word', 'hyphenated_word'])
            ->values([$word, $hyphenatedWord])
            ->execute([$word, $hyphenatedWord])
            ->getData();
    }

    public function deleteWord(int $id): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->delete()
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function updateWord(int $id, string $word, string $hyphenatedWord): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['word', 'hyphenated_word'])
            ->where('id')
            ->execute([$word, $hyphenatedWord, $id])
            ->getData();
        return $this->formWordModel($wordsArray);
    }

    private function formWordModel(array|bool $wordsArray): Word|bool
    {
        if($wordsArray !== false){
            return new Word(
                (int)$wordsArray['id'],
                $wordsArray['word'],
                $wordsArray['hyphenated_word']
            );
        }
        return false;
    }
}