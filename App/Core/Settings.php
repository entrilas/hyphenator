<?php

namespace App\Core;

use App\Core\Database\Export;
use App\Services\PatternReaderService;

class Settings
{
    private array $patterns;

    public function __construct(
        private PatternReaderService $patternReaderService,
        private Export $exportService,
        private $settings
    )
    { }

    /**
     * @throws Exceptions\InvalidArgumentException
     */
    private function importPatterns()
    {
        if($this->isDatabaseValid()) {
            $this->patterns = $this->exportService->exportPatterns();
        }else{
            $this->patterns = $this->patternReaderService->readFile(
                $this->getPatternPath()
            );
        }
    }

    public function isDatabaseValid(): bool
    {
        $databaseStatus = $this->settings['USE_DATABASE'];
        return filter_var($databaseStatus, FILTER_VALIDATE_BOOLEAN);
    }

    private function getPatternPath(): string
    {
        return (dirname(__FILE__, 3)
            . $this->settings['RESOURCES_PATH']
            . DIRECTORY_SEPARATOR
            . $this->settings['PATTERNS_NAME']);
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     */
    public function getPatterns(): array
    {
        $this->importPatterns();
        return $this->patterns;
    }
}