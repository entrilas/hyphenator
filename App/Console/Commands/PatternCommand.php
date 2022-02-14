<?php

namespace App\Console\Commands;

use App\Core\Database\PatternImport;
use App\Core\Database\QueryBuilder;

class PatternCommand
{
    private array $patterns;
    private QueryBuilder $queryBuilder;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
        $this->queryBuilder = QueryBuilder::getInstanceOf();
    }

    public function execute()
    {

    }
}