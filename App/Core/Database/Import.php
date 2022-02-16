<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Cache\Cache;
use App\Core\Exceptions\InvalidArgumentException;
use App\Services\FileReaderService;

class Import
{
    public function __construct(
        private QueryBuilder $queryBuilder,
        private Cache $cache,
        private FileReaderService $fileReaderService
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function importPatterns(string $path): void
    {
        $this->truncateAll();
        $this->validatePath($path);
        $patterns = $this->fileReaderService->readFile($path);
        foreach($patterns as $pattern)
        {
            $this->import('patterns', 'pattern', $pattern);
        }
        $this->setCache($patterns);
    }

    public function import(string $table, string $key, string $value): void
    {
        $this->queryBuilder->insert($table, [
            $key => $value
        ]);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function setCache($patterns): void
    {
        if($this->cache->has('patterns'))
        {
            $this->cache->delete('patterns');
            $this->cache->set('patterns', $patterns);
        }
    }

    private function truncateAll(): void
    {
        $this->queryBuilder->truncate("valid_patterns");
        $this->queryBuilder->truncate("patterns");
        $this->queryBuilder->truncate("words");
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