<?php

namespace App\Models;

use App\Core\Database\QueryBuilder;
use App\Core\Model;

class Word extends Model
{
    private string $table = 'words';

    public function __construct(QueryBuilder $queryBuilder
    ) {
        parent::__construct($queryBuilder);
    }

    public function getWords(): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->execute()
            ->getAllData();
    }

    public function getWord($id): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function getWordByName(string $word): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('word')
            ->execute([$word])
            ->getData();
    }

    public function submitWord(array $params): bool|array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['word', 'hyphenated_word'])
            ->values([$params['word'], $params['hyphenated_word']])
            ->execute([$params['word'], $params['hyphenated_word']])
            ->getData();
    }

    public function deleteWord($id): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->delete()
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function updateWord(array $params): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['word', 'hyphenated_word'])
            ->where('id')
            ->execute([$params['word'], $params['hyphenated_word'], $params[0][0]])
            ->getData();
    }
}
