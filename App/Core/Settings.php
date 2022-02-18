<?php

namespace App\Core;

use App\Constants\Constants;
use App\Core\Database\Export;
use App\Services\PatternReaderService;

class Settings
{
    private array $patterns;
    private $settings;

    public function __construct(
        private PatternReaderService $patternReaderService,
        private Export $exportService,
        private Config $config
    )
    {
        $this->getSettings();
    }

    /**
     * @throws Exceptions\InvalidArgumentException
     */
    private function exportPatterns()
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
        $this->exportPatterns();
        return $this->patterns;
    }

    private function getSettings(): void
    {
        $this->settings = $this->config->get(
            Constants::CONFIG_FILE_NAME
        );
    }
}