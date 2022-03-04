<?php

declare(strict_types=1);

namespace App\Core;

use App\Constants\ConfigConstants;
use App\Constants\Constants;

class Settings
{
    private array $databaseSettings;
    private array $loggerSettings;
    private array $configSettings;

    /**
     * @throws Exceptions\FileNotFoundException
     * @throws Exceptions\UnsupportedFormatException
     */
    public function __construct(
        private Config $config
    ) {
        $this->databaseSettings = $this->config->get(
            Constants::DATABASE_FILE_NAME
        );
        $this->loggerSettings = $this->config->get(
            Constants::LOGGER_FILE_NAME
        );
        $this->configSettings = $this->config->get(
            Constants::CONFIG_FILE_NAME
        );
    }

    public function getDatabaseUsageStatus()
    {
        return $this->configSettings[ConfigConstants::USE_DATABASE_CONFIG_NAME];
    }

    public function getExportToFileStatus()
    {
        return $this->configSettings[ConfigConstants::DATA_EXPORT_TO_FILE_NAME];
    }

    public function getDsn()
    {
        return $this->databaseSettings[ConfigConstants::DSN_NAME];
    }

    public function getPassword()
    {
        return $this->databaseSettings[ConfigConstants::PASSWORD_NAME];
    }

    public function getUsername()
    {
        return $this->databaseSettings[ConfigConstants::USER_NAME];
    }

    public function getExportFileName()
    {
        return $this->configSettings[ConfigConstants::DATA_EXPORT_TO_FILE_NAME];
    }

    public function getLogDateFormatName()
    {
        return $this->loggerSettings[ConfigConstants::LOG_DATE_FORMAT_NAME];
    }

    public function getLogPathName()
    {
        return $this->loggerSettings[ConfigConstants::LOG_PATH_NAME];
    }

    public function getLogFormatName()
    {
        return $this->loggerSettings[ConfigConstants::LOG_FORMAT_NAME];
    }

    public function getLogToConsoleName()
    {
        return $this->loggerSettings[ConfigConstants::LOG_TO_CONSOLE_NAME];
    }

    public function getLogToFileName()
    {
        return $this->loggerSettings[ConfigConstants::LOG_TO_FILE_NAME];
    }

    public function getCacheOutput()
    {
        return $this->configSettings[Constants::CACHE_OUTPUT_NAME];
    }

    public function getPatternsPathName()
    {
        return $this->configSettings[Constants::PATTERNS_PATH_NAME];
    }
}
