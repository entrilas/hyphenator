<?php

namespace App\Models;

use App\Core\Database\QueryBuilder;
use App\Core\Model;

class ValidPattern extends Model
{
    private string $table = 'valid_patterns';

    public function __construct(QueryBuilder $queryBuilder) {
        parent::__construct($queryBuilder);
    }

    public function submitValidPattern(array $params): bool|array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['fk_word_id', 'fk_pattern_id'])
            ->values([$params['fk_word_id'], $params['fk_pattern_id']])
            ->execute([$params['fk_word_id'], $params['fk_pattern_id']])
            ->getData();
    }
}
