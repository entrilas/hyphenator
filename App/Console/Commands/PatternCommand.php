<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Interfaces\CommandInterface;
use App\Constants\Constants;
use App\Core\Database\Import;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\InvalidArgumentException;

class PatternCommand implements CommandInterface
{
    public function __construct(
        private Import $patternImportService,
        private string $path
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws FileNotFoundException
     */
    public function execute(): bool
    {
        return $this->patternImportService->importPatterns($this->path);
    }

    public static function getCommand(): string
    {
        return Constants::IMPORT_PATTERNS_COMMAND;
    }
}
