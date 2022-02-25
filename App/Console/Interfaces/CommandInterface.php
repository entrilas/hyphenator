<?php

declare(strict_types=1);

namespace App\Console\Interfaces;

interface CommandInterface
{
    public function execute() : array|string|bool;

    public static function getCommand(): string;
}
