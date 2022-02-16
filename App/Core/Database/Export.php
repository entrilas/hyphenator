<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Cache\Cache;
use App\Core\Exceptions\InvalidArgumentException;
use App\Services\PatternReaderService;

class Export
{
    public function __construct(
        private QueryBuilder $queryBuilder,
        private Cache $cache
    ) {
    }

    public function exportPatterns()
    {
//        return $this->queryBuilder->getAll('patterns');
    }

    public function export(): void
    {

    }
}
