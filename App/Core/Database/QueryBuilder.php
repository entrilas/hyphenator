<?php

namespace App\Core\Database;

class QueryBuilder
{
    private $database;
    private static $instance;

    public function __construct()
    {
        $this->database = Database::getInstanceOf();
    }

    public static function getInstanceOf()
    {
        if (!self::$instance) {
            self::$instance = new QueryBuilder();
        }
        return self::$instance;
    }


}