<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Interfaces\CommandInterface;
use App\Constants\Constants;
use App\Core\Database\Migration;

class MigrationCommand implements CommandInterface
{
    public function __construct(
        private Migration $migration,
        private string $path
    ) {
    }

    public function execute(): mixed
    {
        return $this->migration->migrate($this->path);
    }

    public static function getCommand(): string
    {
        return Constants::MIGRATE_COMMAND;
    }
}