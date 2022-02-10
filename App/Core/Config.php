<?php

declare(strict_types=1);

namespace App\Core;

use App\Constants\Constants;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Parser\JSONParser;

class Config
{
    private JSONParser $jsonParser;

    public function __construct(JSONParser $jsonParser)
    {
        $this->jsonParser = $jsonParser;
    }

    /**
     * @throws FileNotFoundException
     * @throws Exceptions\ParseException
     * @throws UnsupportedFormatException
     */
    public function get($path)
    {
        $realPath = $this->getPath($path);
        $this->validatePath($realPath);
        $this->validateFormat($realPath);
        return $this->jsonParser->parse($realPath);
    }

    /**
     * @throws UnsupportedFormatException
     */
    private function validateFormat($path)
    {
        $fileInformation = pathinfo($path);
        if (!in_array($fileInformation['extension'], $this->extension())) {
            throw new UnsupportedFormatException('Unsupported configuration format.
             At this moment, only JSON file is supported');
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function validatePath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("Configuration file: [$path] cannot be found");
        }
    }

    public function extension(): array
    {
        return array('json');
    }

    private function getPath(string $name): string
    {
        return dirname(__FILE__, 3) . Constants::CONFIG_PATH . "/" . $name . ".json";
    }
}