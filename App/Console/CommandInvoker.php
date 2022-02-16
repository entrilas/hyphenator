<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Interfaces\CommandInterface;

class CommandInvoker
{
    public function __construct(
        private CommandInterface $command
    ) {
    }

    public function handle(): mixed
    {
        return $this->command->execute();
    }
}