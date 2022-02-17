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

    public function getPatterns()
    {
        return $this->queryBuilder->selectAll(
                $this->table,
                ['id', 'pattern']
        );
    }
}
