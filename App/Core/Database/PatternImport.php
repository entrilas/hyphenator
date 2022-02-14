<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Exceptions\InvalidArgumentException;
use App\Services\PatternReaderService;

class PatternImport
{
    private QueryBuilder $queryBuilder;
    private PatternReaderService $patternReaderService;

    public function __construct(QueryBuilder $queryBuilder, PatternReaderService $patternReaderService)
    {
        $this->queryBuilder = $queryBuilder;
        $this->patternReaderService = $patternReaderService;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function import($path): void
    {
        $this->validatePath($path);
        $patterns = $this->patternReaderService->readFile($path);
        foreach($patterns as $pattern)
        {
            $this->queryBuilder->insert('patterns', [
                'pattern' => $pattern
            ]);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validatePath($path): void
    {
        if(!file_exists($path))
            throw new InvalidArgumentException("File in provided path [$path] does not exist.");
    }
}