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

    public function getWords(): bool|string
    {
        return $this->queryBuilder->selectAll(
            $this->table,
            ['id', 'word', 'hyphenated_word']
        );
    }

    public function getWord($id): bool|string
    {
        return $this->queryBuilder->select(
            $this->table,
            ['id', 'word', 'hyphenated_word'],
            'id',
            $id
        );
    }

    public function submitWord(array $params)
    {
        return $this->queryBuilder->insert(
            $this->table,
            [$params['word'], $params['hyphenated_word']],
            ['word', 'hyphenated_word']
        );
    }

    public function deleteWord(array $params): bool|string
    {
        return $this->queryBuilder->delete(
            $this->table,
            $params[0],
            'id'
        );
    }

    public function updateWord(array $params): bool|string
    {
        return $this->queryBuilder->update(
            $this->table,
            ['word', 'hyphenated_word'],
            [$params['word'], $params['hyphenated_word'], $params[0][0]],
            'id'
        );

    }
}
