<?php

namespace App\Services;

use App\Core\Cache\Cache;

class PatternReaderService extends FileReader
{
    private $cache;

    public function __construct(){
        $this->cache = Cache::getInstanceOf();
    }

    public function readFile($path){
        if(!$this->cache->has('patterns')) {
            $patterns = parent::readFile($path);
            $this->cache->set('patterns', $patterns);
            return $patterns;
        }else{
            return $this->cache->get('patterns');
        }
    }
}