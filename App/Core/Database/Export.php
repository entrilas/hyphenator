<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Repository\PatternRepository;

class Export
{
    public function __construct(
        private PatternRepository $patternRepository
    ) {
    }

    public function exportPatterns(): array
    {
        $patternsArray = $this->patternRepository->getPatterns();
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
