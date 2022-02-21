<?php

namespace App\Core;

use App\Constants\Constants;
use App\Core\Database\Export;
use App\Core\Log\Logger;
use App\Services\PatternReaderService;
use PDOException;

class Settings
{
    private array $patterns;
    private $settings;

    /**
     * @throws Exceptions\FileNotFoundException
     * @throws Exceptions\UnsupportedFormatException
     */
    public function __construct(
        private PatternReaderService $patternReaderService,
        private Export $exportService,
        private Config $config,
        private Logger $logger
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
            try{
                $this->patterns = $this->exportService->exportPatterns();
            }catch(PDOException $e)
            {
                $this->patterns = $this->patternReaderService->readFile(
                    $this->getPatternPath()
                );
                $this->logger->warning("Tables in database does not exist, patterns are taken from
                file. You should migrate tables/insert patterns into database.");
                throw new PDOException($e->getCode());
            }
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

    /**
     * @throws Exceptions\FileNotFoundException
     * @throws Exceptions\UnsupportedFormatException
     */
    private function getSettings(): void
    {
        $this->settings = $this->config->get(
            Constants::CONFIG_FILE_NAME
        );
    }
}