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

    public function submitValidPattern(array $params)
    {
        return $this->queryBuilder->insert(
            $this->table,
            [$params['fk_word_id'], $params['fk_pattern_id']],
            ['fk_word_id', 'fk_pattern_id']
        );
    }
}

