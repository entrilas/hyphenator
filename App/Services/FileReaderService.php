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
        $file = new SplFileObject($path);
        $this->setFileFlags($file);
        foreach ($file as $line) {
            $data[] = $line;
        }
        return $data;
    }

    /**
     * @throws FileNotFoundException
     */
    private function validatePath(string $filePath): void
    {
        $absPath = realpath($filePath);
        if ($absPath === false) {
            throw new FileNotFoundException(sprintf("File with URL [ %s ] was not found.", $filePath));
        }
    }

    private function setFileFlags($file): void
    {
        $file->setFlags(
            SplFileObject::SKIP_EMPTY |
            SplFileObject::READ_AHEAD
        );
    }
}
