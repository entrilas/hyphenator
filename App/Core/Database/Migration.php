<?php

namespace App\Core\Database;

use App\Constants\Constants;
use App\Core\Log\Logger;
use Exception;

class Migration
{
    private $database;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->database = Database::getInstanceOf()->getConnector();
        $this->logger = $logger;
    }

    /**
     * @throws Exception
     */
    public function migrate(string $name): void
    {
        $path = $this->getPath($name);
        $this->validateFile($path);
        $sql = file_get_contents($path);
        $this->execSQL($sql, $name);
    }

    /**
     * @throws Exception
     */
    private function validateFile(string $path): void
    {
        if(!file_exists($path))
            throw new Exception("File in location [$path] does not exist.");
    }

    private function execSQL(string $sql, string $name): void
    {
        try {
            $this->database->exec($sql);
            $this->logger->info("Migration with name [$name] is successful");
        }catch(Exception $e){
            $this->logger->error("Migration with name [$name] has failed!");
        }
    }

    private function getPath(string $name): string
    {
        return dirname(__FILE__, 3)
            . DIRECTORY_SEPARATOR
            . Constants::MIGRATIONS_FOLDER_NAME
            . DIRECTORY_SEPARATOR
            . $name
            . Constants::MIGRATION_FILE_EXTENSION;
    }
}