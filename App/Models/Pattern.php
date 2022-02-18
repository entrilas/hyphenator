<?php

namespace App\Models;

use App\Core\Database\QueryBuilder;
use App\Core\Model;

class Pattern extends Model
{
    private string $table = 'patterns';

    public function __construct(QueryBuilder $queryBuilder) {
        parent::__construct($queryBuilder);
    }

    public function getPatterns(): bool|string
    {
        return $this->queryBuilder->selectAll(
                $this->table,
                ['id', 'pattern']
        );
    }

    public function getPattern($id): bool|string
    {
        return $this->queryBuilder->select(
            $this->table,
            ['id', 'pattern'],
            'id',
            $id
        );
    }


    public function submitPattern(array $params)
    {
        return $this->queryBuilder->insert(
            $this->table,
            [$params['pattern']],
            ['pattern',]
        );
    }

    public function deletePattern(array $params): bool|string
    {
        return $this->queryBuilder->delete(
            $this->table,
            $params[0],
            'id'
        );
    }

    public function updatePattern(array $params): bool|string
    {
        return $this->queryBuilder->update(
            $this->table,
            ['pattern'],
            [$params['pattern'], $params[0][0]],
            'id'
        );

    }
}
