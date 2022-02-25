<?php

namespace App\Core\Cache;

use App\Constants\Constants;
use App\Core\Cache\Interfaces\CacheInterface;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Settings;
use Generator;
use DateInterval;
use Traversable;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Cache implements CacheInterface
{
    public const PSR16_RESERVED = '/{|}|\(|\)|\/|\\\\|@|:/u';
    private string $cachePath;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        private Settings $settings,
        private int $defaultTTL = Constants::DEFAULT_TTL,
        private int $dirMode = Constants::DIR_MODE,
        private int $fileMode = Constants::FILE_MODE
    ) {
        $this->cachePath = $this->realPath($this->settings->getCacheOutput());
        if (! file_exists($this->cachePath) && file_exists(dirname($this->cachePath))) {
            $this->makeDirectory($this->cachePath);
        }

        $path = realpath($this->cachePath);

        if ($path === false) {
            throw new InvalidArgumentException(sprintf("Cache path (%s) does not exist", $this->cachePath));
        }

        if (!is_writable($path . DIRECTORY_SEPARATOR)) {
            throw new InvalidArgumentException(sprintf("Cache path (%s) is not writable", $this->cachePath));
        }

        $this->cachePath = $path;
    }

    private function realPath(string $name): string
    {
        return dirname(__FILE__, 4)
            . $name;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        $path = $this->getPath($key);

        $expires_at = filemtime($path);

        if ($expires_at === false) {
            return $default;
        }

        if ($this->getTime() >= $expires_at) {
            unlink($path);

            return $default;
        }

        $data = file_get_contents($path);

        if ($data === false) {
            return $default;
        }

        if ($data === 'b:0;') {
            return false;
        }

        $value = unserialize($data);

        if ($value === false) {
            return $default;
        }

        return $value;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function set($key, $value, $ttl = null): bool
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
            throw new InvalidArgumentException("Incorrect TTL");
        }

        if (false === file_put_contents($temp_path, serialize($value))) {
            return false;
        }

        if (false === chmod($temp_path, $this->fileMode)) {
            return false;
        }

        if (touch($temp_path, $expires_at) && rename($temp_path, $path)) {
            return true;
        }

        unlink($temp_path);

        return false;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function delete($key): bool
    {
        $this->validateKey($key);

        $path = $this->getPath($key);

        return !file_exists($path) || unlink($path);
    }

    public function clear(): bool
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

    /**
     * @throws InvalidArgumentException
     */
    public function getMultiple($keys, $default = null): array
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new InvalidArgumentException("Keys must be either of type array or Traversable");
        }

        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->get($key) ?: $default;
        }

        return $values;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setMultiple($values, $ttl = null): bool
    {
        if (! is_array($values) && ! $values instanceof Traversable) {
            throw new InvalidArgumentException("Keys must be either of type array or Traversable");
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

    /**
     * @throws InvalidArgumentException
     */
    public function deleteMultiple($keys): bool
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new InvalidArgumentException("Keys must be either type of Array or Traversable");
        }

        $ok = true;

        foreach ($keys as $key) {
            $this->validateKey($key);

            $ok = $ok && $this->delete($key);
        }

        return $ok;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function has($key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function getPath($key): string
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

    protected function getTime(): int
    {
        return time();
    }

    protected function listPaths(): Generator
    {
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

    /**
     * @throws InvalidArgumentException
     */
    protected function validateKey($key)
    {
        if (! is_string($key)) {
            $type = is_object($key) ? get_class($key) : gettype($key);

            throw new InvalidArgumentException(sprintf("Wrong key type (%s) provided", $type));
        }

        if ($key === "") {
            throw new InvalidArgumentException("Wrong key: empty string provided");
        }

        if (preg_match(self::PSR16_RESERVED, $key, $match) === 1) {
            throw new InvalidArgumentException(sprintf("Invalid character key provided : %s", $match));
        }
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
