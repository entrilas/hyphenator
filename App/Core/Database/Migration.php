<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Constants\Constants;
use App\Core\Log\Logger;
use Exception;

class Migration
{
    public function __construct(
        private Logger $logger,
        private Database $database
    ) {
    }

    /**
     * @throws Exception
     */
    public function migrate(string $name): bool
    {
        $path = $this->getPath($name);
        $this->validateFile($path);
        $sql = file_get_contents($path);
        $this->execSQL($sql, $name);
        return true;
    }

    /**
     * @throws Exception
     */
    private function validateFile(string $path): void
    {
        if(!file_exists($path))
            throw new Exception(sprintf("File location with path [ $path ] does not exist", $path));
    }

    private function execSQL(string $sql, string $name): void
    {
        try {
            $this->database->getConnector()->exec($sql);
            $this->logger->info(sprintf("Migration with name [ %s ] is successful", $name));
        }catch(Exception $e){
            $this->logger->error(sprintf("Migration with name [ %s ] is unsuccessful", $name));
        }
    }

    private function getPath(string $name): string
    {
        return dirname(__FILE__, 3)
            . DIRECTORY_SEPARATOR
            . Constants::MIGRATIONS_FOLDER_NAME
            . DIRECTORY_SEPARATOR
            . $name;
    }
}
