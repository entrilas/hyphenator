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

    public function exportPatterns(): array
    {
        $patternsJson = $this->queryBuilder->selectAll("patterns", ['pattern']);
        $patternsArray = json_decode($patternsJson, true);
        return $this->formPatterns($patternsArray);
    }

    private function formPatterns($patternsArray): array
    {
        $patterns = [];
        foreach ($patternsArray as $pattern) {
            $patterns[] = $pattern['pattern'];
        }
        return $patterns;
    }
}
