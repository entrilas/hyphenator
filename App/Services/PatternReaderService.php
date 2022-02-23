<?php

namespace App\Services;

use App\Core\Cache\Cache;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\InvalidArgumentException;

class PatternReaderService extends FileReaderService
{
    public function __construct(
        private Cache $cache
    ) {
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function readFile(string $path): array
    {
        if(!$this->cache->has('patterns')) {
            $patterns = parent::readFile($path);
            $this->cache->set('patterns', $patterns);
            return $patterns;
        }else{
            return $this->cache->get('patterns');
        }
    }
}