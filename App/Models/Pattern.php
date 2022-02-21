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
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->execute()
            ->getAllJson();
    }

    public function getPattern($id): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getJson();
    }

    public function getPatternByName(string $pattern): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('pattern')
            ->execute([$pattern])
            ->getJson();
    }

    public function submitPattern(array $params)
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['pattern'])
            ->values([$params['pattern']])
            ->execute([$params['pattern']])
            ->getJson();
    }

    public function deletePattern($id): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->delete()
            ->from()
            ->where('id')
            ->execute([$id])
            ->getJson();
    }

    public function updatePattern(array $params): bool|string
    {
        return $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['pattern'])
            ->where('id')
            ->execute([$params['pattern'], $params[0][0]])
            ->getJson();
    }
}
