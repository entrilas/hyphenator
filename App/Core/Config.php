<?php

declare(strict_types=1);

namespace App\Core;

use App\Constants\Constants;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\UnsupportedFormatException;

class Config
{
    /**
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     */
    public function get(string $path): array
    {
        $realPath = $this->getPath($path);
        $this->validatePath($realPath);
        $this->validateFormat($realPath);
        return $this->parse($realPath);
    }

    /**
     * @throws UnsupportedFormatException
     */
    private function validateFormat(string $path): void
    {
        $fileInformation = pathinfo($path);
        if ($fileInformation['extension'] != "json") {
            throw new UnsupportedFormatException('Unsupported configuration format.
             At this moment, only JSON file is supported');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function validatePath(string $path): void
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("Configuration file: [$path] cannot be found");
        }
    }

    public function parse(string $path): array
    {
        return json_decode(file_get_contents($path), true);
    }

    private function getPath(string $name): string
    {
        return dirname(__FILE__, 3)
            . DIRECTORY_SEPARATOR
            . Constants::CONFIG_PATH
            . DIRECTORY_SEPARATOR
            . $name;
    }
}