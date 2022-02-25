<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Settings;
use Exception;
use PDO;
use PDOException;

class Database
{
    private $connector = null;

    /**
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     * @throws Exception
     */
    public function __construct(
        private Settings $settings
    ) {
        $this->connection();
    }

    /**
     * @throws Exception
     */
    public function connection(): void
    {
        if (null === $this->connector) {
            try {
                $this->connector = new PDO(
                    $this->settings->getDsn(),
                    $this->settings->getUsername(),
                    $this->settings->getPassword()
                );
                $this->connector->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch(PDOException $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function getConnector()
    {
        return $this->connector;
    }
}
