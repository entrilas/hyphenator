<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Interfaces\CommandInterface;

class CommandInvoker
{
    private CommandInterface $command;

    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
    }
    public function setCommand(CommandInterface $command) : void
    {
        $this->command = $command;
    }
    public function handle(): mixed
    {
        return $this->command->execute();
    }
}