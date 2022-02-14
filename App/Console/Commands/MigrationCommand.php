<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Interfaces\CommandInterface;
use App\Constants\Constants;
use App\Core\Database\Migration;

class MigrationCommand implements CommandInterface
{
    private Migration $migration;

    public function __construct(Migration $migration)
    {
        $this->migration = $migration;
    }

    public function execute(): mixed
    {
        return $this->migration->migrate('migrate');
    }
}