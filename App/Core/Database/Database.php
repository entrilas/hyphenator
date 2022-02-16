<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Constants\Constants;
use App\Core\Config;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\ParseException;
use App\Core\Exceptions\UnsupportedFormatException;
use Exception;
use PDO;
use PDOException;

class Database
{
    private $connector = null;

    /**
     * @throws ParseException
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     * @throws Exception
     */
    public function __construct(private $config)
    {
        $this->config = $this->config->get(Constants::DATABASE_FILE_NAME);
        $this->connection();
    }

    /**
     * @throws Exception
     */
    public function connection($new = false): void
    {
        if (null === $this->connector || true === $new) {
            try {
                $this->connector = new PDO($this->config['dsn'], $this->config['user'], $this->config['password']);
                $this->connector->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
