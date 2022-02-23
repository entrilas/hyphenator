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

    public function getPatterns(): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->execute()
            ->getAllData();
    }

    public function getPattern(int $id): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function getPatternByName(string $pattern): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('pattern')
            ->execute([$pattern])
            ->getData();
    }

    public function submitPattern(array $params): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['pattern'])
            ->values([$params['pattern']])
            ->execute([$params['pattern']])
            ->getData();
    }

    public function deletePattern($id): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->delete()
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function updatePattern(array $params): array|bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['pattern'])
            ->where('id')
            ->execute([$params['pattern'], $params[0][0]])
            ->getData();
    }
}
