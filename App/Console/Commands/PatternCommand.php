<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Interfaces\CommandInterface;
use App\Core\Database\Import;
use App\Core\Database\QueryBuilder;
use App\Core\Exceptions\InvalidArgumentException;

class PatternCommand implements CommandInterface
{
    private array $patterns;
    private QueryBuilder $queryBuilder;
    private Import $patternImportService;
    private string $path;

    public function __construct(array $patterns, Import $patternImportService, string $path)
    {
        $this->patterns = $patterns;
        $this->queryBuilder = QueryBuilder::getInstanceOf();
        $this->patternImportService = $patternImportService;
        $this->path = $path;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(): mixed
    {
        return $this->patternImportService->importPatterns($this->path);
    }
}