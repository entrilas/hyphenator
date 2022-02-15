<?php

namespace App\Core\Database;

use App\Constants\Constants;
use App\Core\Config;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\ParseException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Parser\JSONParser;
use Exception;
use PDO;
use PDOException;

class Database
{
    private $connector = null ;
    private $config;
    private static $instance = null;

    /**
     * @throws ParseException
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     * @throws Exception
     */
    public function __construct()
    {
        $this->config = new Config(new JSONParser());
        $this->config = $this->config->get(Constants::DATABASE_FILE_NAME);
        $this->connection();
    }

    /**
     * @throws Exception
     */
    public function connection($new = false)
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

    public static function getInstanceOf(): ?Database
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnector()
    {
        return $this->connector;
    }
}