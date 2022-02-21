<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Constants\Constants;
use App\Core\Config;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\UnsupportedFormatException;
use Exception;
use PDO;
use PDOException;

class Database
{
    private $connector = null;
    private $settings;

    /**
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     * @throws Exception
     */
    public function __construct(private Config $config)
    {
        $this->settings = $this->config->get(Constants::DATABASE_FILE_NAME);
        $this->connection();
    }

    /**
     * @throws Exception
     */
    public function connection(): void
    {
        if (null === $this->connector) {
            try {
                $this->connector = new PDO($this->settings['dsn'], $this->settings['user'], $this->settings['password']);
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
