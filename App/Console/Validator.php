<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\PatternCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Constants\Constants;
use App\Core\Exceptions\InvalidArgumentException;

class Validator
{
    private mixed $argv;
    private mixed $argc;

    public function __construct()
    {
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateCommand(): void
    {
        if ($this->getFlag() === FileCommand::getCommand() ||
            $this->getFlag() === SentenceCommand::getCommand() ||
            $this->getFlag() === WordCommand::getCommand() ||
            $this->getFlag() === MigrationCommand::getCommand() ||
            $this->getFlag() === PatternCommand::getCommand()
        ) {
        }else{
            throw new InvalidArgumentException("Command does not exist 
            (php index.php [flag] '[content]')");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateData(): void
    {
        if(($this->getData() === null || $this->getData() === '')
            && ($this->getFlag() === Constants::IMPORT_PATTERNS_COMMAND))
            throw new InvalidArgumentException("Data provided is null or empty");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateArguments(): void
    {
        if($this->argc > 3)
            throw new InvalidArgumentException("Invalid arguments provided 
            (php index.php [flag] '[content]')");
    }

    public function getFlag(): mixed
    {
        if(isset($this->argv[1]))
            return $this->argv[1];
        return null;
    }

    public function getData(): mixed
    {
        if(isset($this->argv[2]))
            return $this->argv[2];
        return null;
    }
}