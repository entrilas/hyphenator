<?php

namespace App\Core;

use App\Constants\Constants;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Parser\JSONParser;

class Config
{
    public function get($path)
    {
        $realPath = $this->getPath($path);
        $pathInfo = $this->checkValidPath($realPath);
        $info = pathinfo($pathInfo);
        $extension = isset($info['extension']) ? $info['extension'] : '';
        $parser = new JSONParser();
        $this->checkFileFormat($extension, $parser);
        return $parser->parse($realPath);
    }

    private function checkFileFormat($extension, $parser)
    {
        if (!in_array($extension, $parser->extension())) {
            throw new UnsupportedFormatException('Unsupported configuration format.
             At this moment, only JSON file is supported');
        }
    }

    private function checkValidPath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("Configuration file: [$path] cannot be found");
        }

        return $path;
    }

    private function getPath(string $name)
    {
        return dirname(__FILE__, 3) . Constants::CONFIG_PATH . $name . ".json";
    }
}