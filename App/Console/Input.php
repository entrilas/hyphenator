<?php

declare(strict_types=1);

namespace App\Console;

class Input
{
    private mixed $argv;
    private mixed $argc;

    public function __construct()
    {
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
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

    public function getArgumentsCount(): int
    {
        return $this->argc;
    }
}
