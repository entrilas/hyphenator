<?php

declare(strict_types=1);

namespace App\Services;

use SplFileObject;

class FileReaderService
{
    public function readFile(string $path): array
    {
        $data = [];
        foreach (new SplFileObject($path) as $line) {
            $data[] = $line;
        }
        return $data;
    }
}