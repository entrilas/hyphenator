<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Database\Export;
use App\Core\Log\Logger;
use App\Services\PatternReaderService;

class Patterns
{
    private array $patterns;
    private array $patternsSettings;

    /**
     * @throws Exceptions\InvalidArgumentException|Exceptions\FileNotFoundException
     */
    public function __construct(
        private PatternReaderService $patternReaderService,
        private Export $exportService,
        private Config $config,
        private Logger $logger,
        private Settings $settings
    ) {
        $this->patternsSettings = $this->settings->getConfig();
        $this->exportPatterns();
    }

    /**
     * @throws Exceptions\InvalidArgumentException|Exceptions\FileNotFoundException
     */
    private function exportPatterns()
    {
        if($this->patternsSettings['USE_DATABASE']) {
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
            . $this->patternsSettings['PATTERNS_PATH'];
    }

    public function getPatterns(): array
    {
        return $this->patterns;
    }
}
