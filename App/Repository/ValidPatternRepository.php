<?php

namespace App\Repository;

use App\Core\Connection;
use App\Core\Database\QueryBuilder;
use App\Repository\Interfaces\IValidPatternRepository;

class ValidPatternRepository extends Connection implements IValidPatternRepository
{
    private string $table = 'valid_patterns';

    public function __construct(QueryBuilder $queryBuilder) {
        parent::__construct($queryBuilder);
    }

    public function submitValidPattern(int $fk_word_id, int $fk_pattern_id): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['fk_word_id', 'fk_pattern_id'])
            ->values([$fk_word_id, $fk_pattern_id])
            ->execute([$fk_word_id, $fk_pattern_id])
            ->getData();
    }
}