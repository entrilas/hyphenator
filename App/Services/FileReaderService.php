<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Exceptions\FileNotFoundException;
use SplFileObject;

class FileReaderService
{
    /**
     * @throws FileNotFoundException
     */
    public function readFile(string $path): array
    {
        $this->validatePath($path);
        $data = [];
        foreach (new SplFileObject($path) as $line) {
            $data[] = $line;
        }
        return $data;
    }

    /**
     * @throws FileNotFoundException
     */
    private function validatePath(string $filePath)
    {
        $absPath = realpath($filePath);
        if ($absPath === false) {
            throw new FileNotFoundException(sprintf("File with URL [ %s ] was not found.", $filePath));
        }
    }
}