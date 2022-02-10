<?php

declare(strict_types=1);

namespace App\Services;

class FileReaderService
{
    public function readFile($path): array
    {
        $handle = fopen($path, "r");
        if ($handle) {
            while (!feof($handle)) {
                $patterns[] = fgets($handle, 4096);
            }
            fclose($handle);
        }
        return $patterns;
    }
}