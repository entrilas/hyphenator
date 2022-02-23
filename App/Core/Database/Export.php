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
        $patternsArray = $this->pattern->getPatterns();
        return $this->formPatterns($patternsArray);
    }

    private function formPatterns(array $patternsArray): array
    {
        $patterns = [];
        foreach ($patternsArray as $pattern) {
            $patternData = (array)$pattern;
            $patterns[] = $patternData['pattern'];
        }
        return $patterns;
    }
}
