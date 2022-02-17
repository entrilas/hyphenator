<?php

namespace App\Core;

use App\Core\Database\QueryBuilder;

abstract class Model {

    protected QueryBuilder $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }
}