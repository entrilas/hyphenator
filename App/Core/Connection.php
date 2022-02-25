<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Database\QueryBuilder;

abstract class Connection {

    protected QueryBuilder $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }
}
