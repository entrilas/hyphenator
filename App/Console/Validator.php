<?php

declare(strict_types=1);

namespace App\Console;

use App\Constants\Constants;
use App\Core\Exceptions\InvalidArgumentException;

class Validator
{
    private static mixed $argv;
    private static mixed $argc;
    private static $instance;

    public function __construct()
    {
        self::$argv = $_SERVER['argv'];
        self::$argc = $_SERVER['argc'];
    }

    public static function getInstanceOf(): Validator
    {
        if (!self::$instance) {
            self::$instance = new Validator();
        }
        return self::$instance;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function validateCommand(): void
    {
        if (self::getFlag() == Constants::FILE_COMMAND ||
            self::getFlag() == Constants::SENTENCE_COMMAND ||
            self::getFlag() == Constants::WORD_COMMAND ||
            self::getFlag() == Constants::MIGRATE_COMMAND ||
            self::getFlag() == Constants::IMPORT_PATTERNS_COMMAND
        ) {
        }else{
            throw new InvalidArgumentException("Command does not exist 
            (php index.php [flag] '[content]')");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function validateData(): void
    {
        if((self::getData() == null || self::getData() == '')
            && (self::getFlag() == Constants::IMPORT_PATTERNS_COMMAND))
            throw new InvalidArgumentException("Data provided is null or empty");
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function validateArguments(): void
    {
        if(self::$argc > 3)
            throw new InvalidArgumentException("Invalid arguments provided 
            (php index.php [flag] '[content]')");
    }

    public static function getFlag(): mixed
    {
        if(isset(self::$argv[1]))
            return self::$argv[1];
        return null;
    }

    public static function getData(): mixed
    {
        if(isset(self::$argv[2]))
            return self::$argv[2];
        return null;
    }
}