<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Cache\Cache;
use App\Core\Exceptions\InvalidArgumentException;
use App\Services\PatternReaderService;

class Export
{
    private QueryBuilder $queryBuilder;
    private $cache;

    public function __construct()
    {
        $this->queryBuilder = QueryBuilder::getInstanceOf();
        $this->cache = Cache::getInstanceOf();
    }

    public function exportPatterns()
    {
//        return $this->queryBuilder->getAll('patterns');
    }

    public function export(): void
    {

    }
}
