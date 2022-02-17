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

    public function getWords()
    {
        return $this->queryBuilder->selectAll(
            $this->table,
            ['id', 'word', 'hyphenated_word']
        );
    }

    public function getWord($id)
    {
        return $this->queryBuilder->select(
            $this->table,
            ['id', 'word', 'hyphenated_word'],
            'id',
            $id
        );
    }
}
