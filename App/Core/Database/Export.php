<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Models\Pattern;

class Export
{
    public function __construct(
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
