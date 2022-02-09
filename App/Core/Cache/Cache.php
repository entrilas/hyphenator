<?php

namespace App\Core\Cache;

use App\Constants\Constants;
use App\Core\Cache\Interfaces\CacheInterface;

class Cache implements CacheInterface
{
    const PSR16_RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';
    private $cachePath;
    private $defaultTTL;
    private $dirMode;
    private $fileMode;
    private static $instance;

    public function __construct(
        string $cachePath,
        int $defaultTtl,
        int $dirMode = 0775,
        int $fileMode = 0664
    ) {
        $this->defaultTTL = $defaultTtl;
        $this->dirMode = $dirMode;
        $this->fileMode = $fileMode;

        if (! file_exists($cachePath) && file_exists(dirname($cachePath))) {
            $this->makeDirectory($cachePath);
        }

        $path = realpath($cachePath);

        if ($path === false) {
            throw new \InvalidArgumentException("Cache path doesn't exist: {$cachePath}");
        }

        if (! is_writable($path . DIRECTORY_SEPARATOR)) {
            throw new \InvalidArgumentException("Cache path isn't writable: {$cachePath}");
        }

        $this->cachePath = $path;
    }

    public function get($key, $default = null)
    {
        $path = $this->getPath($key);

        $expires_at = @filemtime($path);

        if ($expires_at === false) {
            return $default;
        }

        if ($this->getTime() >= $expires_at) {
            @unlink($path);

            return $default;
        }

        $data = @file_get_contents($path);

        if ($data === false) {
            return $default;
        }

        if ($data === 'b:0;') {
            return false;
        }

        $value = @unserialize($data);

        if ($value === false) {
            return $default;
        }

        return $value;
    }

    public function set($key, $value, $ttl = null)
    {
        $path = $this->getPath($key);

        $dir = dirname($path);

        if (! file_exists($dir)) {
            $this->makeDirectory($dir);
        }

        $temp_path = $this->cachePath . DIRECTORY_SEPARATOR . uniqid('', true);

        if (is_int($ttl)) {
            $expires_at = $this->getTime() + $ttl;
        } elseif ($ttl instanceof DateInterval) {
            $expires_at = date_create_from_format("U", $this->getTime())->add($ttl)->getTimestamp();
        } elseif ($ttl === null) {
            $expires_at = $this->getTime() + $this->defaultTTL;
        } else {
            throw new \InvalidArgumentException("Wrong TTL: " . print_r($ttl, true));
        }

        if (false === @file_put_contents($temp_path, serialize($value))) {
            return false;
        }

        if (false === @chmod($temp_path, $this->fileMode)) {
            return false;
        }

        if (@touch($temp_path, $expires_at) && @rename($temp_path, $path)) {
            return true;
        }

        @unlink($temp_path);

        return false;
    }

    public function delete($key)
    {
        $this->validateKey($key);

        $path = $this->getPath($key);

        return !file_exists($path) || @unlink($path);
    }

    public function clear()
    {
        $success = true;

        $paths = $this->listPaths();

        foreach ($paths as $path) {
            if (! unlink($path)) {
                $success = false;
            }
        }
        return $success;
    }

    public function getMultiple($keys, $default = null)
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new \InvalidArgumentException("keys must be either of type array or Traversable");
        }

        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->get($key) ?: $default;
        }

        return $values;
    }

    public function setMultiple($values, $ttl = null)
    {
        if (! is_array($values) && ! $values instanceof Traversable) {
            throw new \InvalidArgumentException("keys must be either of type array or Traversable");
        }

        $ok = true;

        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $key = (string) $key;
            }

            $this->validateKey($key);

            $ok = $this->set($key, $value, $ttl) && $ok;
        }

        return $ok;
    }

    public function deleteMultiple($keys)
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new \InvalidArgumentException("Keys must be either type of Array or Traversable");
        }

        $ok = true;

        foreach ($keys as $key) {
            $this->validateKey($key);

            $ok = $ok && $this->delete($key);
        }

        return $ok;
    }

    public function has($key)
    {
        return $this->get($key, $this) !== $this;
    }

    protected function getPath($key)
    {
        $this->validateKey($key);

        $hash = hash("sha256", $key);

        return $this->cachePath
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[0])
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[1])
            . DIRECTORY_SEPARATOR
            . substr($hash, 2);
    }

    protected function getTime(){
        return time();
    }

    protected function listPaths(){
        $iterator = new RecursiveDirectoryIterator(
            $this->cachePath,
            FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
        );

        $iterator = new RecursiveIteratorIterator($iterator);

        foreach ($iterator as $path) {
            if (is_dir($path)) {
                continue;
            }
            yield $path;
        }
    }

    protected function validateKey($key)
    {
        if (! is_string($key)) {
            $type = is_object($key) ? get_class($key) : gettype($key);

            throw new \InvalidArgumentException("Wrong key type: {$type} provided");
        }

        if ($key === "") {
            throw new \InvalidArgumentException("Wrong key: empty string provided");
        }

        if ($key === null) {
            throw new \InvalidArgumentException("Wrong key: null provided");
        }

        if (preg_match(self::PSR16_RESERVED, $key, $match) === 1) {
            throw new \InvalidArgumentException("Invalid character in key provided: {$match[0]}");
        }
    }

    public static function getInstanceOf()
    {
        if (!self::$instance) {
            self::$instance = new Cache(
                Constants::CACHE_PATH,
                Constants::DEFAULT_TTL,
                Constants::DIR_MODE,
                Constants::FILE_MODE);
        }
        return self::$instance;
    }

    private function makeDirectory($path)
    {
        $parent_path = dirname($path);
        if (!file_exists($parent_path)) {
            $this->makeDirectory($parent_path);
        }
        mkdir($path);
        chmod($path, $this->dirMode);
    }
}