<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Database\Export;
use App\Services\PatternReaderService;

class Patterns
{
    private array $patterns;

    /**
     * @throws Exceptions\InvalidArgumentException|Exceptions\FileNotFoundException
     */
    public function __construct(
        private PatternReaderService $patternReaderService,
        private Export $exportService,
        private Settings $settings
    ) {
        $this->exportPatterns();
    }

    /**
     * @throws Exceptions\InvalidArgumentException|Exceptions\FileNotFoundException
     */
    private function exportPatterns()
    {
        if($this->settings->getDatabaseUsageStatus()) {
                $this->patterns = $this->exportService->exportPatterns();
        }else{
            $this->patterns = $this->patternReaderService->readFile(
                $this->getPatternPath()
            );
        }
    }

    private function getPatternPath(): string
    {
        return dirname(__FILE__, 3)
            . $this->settings->getPatternsPathName();
    }

    public function getPatterns(): array
    {
        return $this->patterns;
    }
}
