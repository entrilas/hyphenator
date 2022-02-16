<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Interfaces\CommandInterface;
use App\Core\Database\Import;
use App\Core\Database\QueryBuilder;
use App\Core\Exceptions\InvalidArgumentException;

class PatternCommand implements CommandInterface
{
    public function __construct(
        private array $patterns,
        private Import $patternImportService,
        private string $path
    ) {

    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(): mixed
    {
        return $this->patternImportService->importPatterns($this->path);
    }
}