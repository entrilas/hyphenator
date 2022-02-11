<?php

declare(strict_types=1);

namespace App\Services;

use SplFileObject;

class FileReaderService
{
    public function readFile($path): array
    {
        foreach (new SplFileObject($path) as $line) {
            $data[] = $line;
        }
        return $data;
    }
}