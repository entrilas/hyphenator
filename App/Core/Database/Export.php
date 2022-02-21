<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Cache\Cache;
use App\Core\Exceptions\InvalidArgumentException;
use App\Models\Pattern;
use App\Services\PatternReaderService;

class Export
{
    public function __construct(
        private QueryBuilder $queryBuilder,
        private Cache $cache,
        private Pattern $pattern
    ) {
    }

    public function exportPatterns(): array
    {
        $patternsJson = $this->pattern->getPatterns();
        $patternsArray = json_decode($patternsJson, true);
        return $this->formPatterns($patternsArray);
    }

    private function formPatterns(array $patternsArray): array
    {
        $patterns = [];
        foreach ($patternsArray as $pattern) {
            $patterns[] = $pattern['pattern'];
        }
        return $patterns;
    }
}
