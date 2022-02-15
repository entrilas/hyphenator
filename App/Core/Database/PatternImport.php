<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Cache\Cache;
use App\Core\Exceptions\InvalidArgumentException;
use App\Services\PatternReaderService;

class PatternImport
{
    private QueryBuilder $queryBuilder;
    private PatternReaderService $patternReaderService;
    private $cache;

    public function __construct(PatternReaderService $patternReaderService)
    {
        $this->queryBuilder = QueryBuilder::getInstanceOf();
        $this->patternReaderService = $patternReaderService;
        $this->cache = Cache::getInstanceOf();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function import($path): void
    {
        $this->truncateAll();
        $this->validatePath($path);
        $patterns = $this->patternReaderService->readFile($path);
        foreach($patterns as $pattern)
        {
            $this->queryBuilder->insert('patterns', [
                'pattern' => $pattern
            ]);
        }
        $this->setCache($patterns);
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