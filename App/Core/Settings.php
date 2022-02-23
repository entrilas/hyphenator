<?php

namespace App\Core;

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
    ){
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

    public function getDatabaseConfig(): array
    {
        return $this->databaseSettings;
    }

    public function getLoggerConfig(): array
    {
        return $this->loggerSettings;
    }

    public function getConfig(): array
    {
        return $this->configSettings;
    }
}
