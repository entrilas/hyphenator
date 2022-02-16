<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Exceptions\FileNotFoundException;
use App\Services\FileReaderService;

class FileHyphenation
{
    public function __construct(
        private HyphenationInterface $hyphenator,
        private FileReaderService $fileReader
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function hyphenateFile(string $filePath): array
    {
        $this->validatePath($filePath);
        $fileData = $this->getDataFromFile($filePath);
        $hyphenatedData = [];
        foreach($fileData as $word)
        {
            $hyphenatedData[] = $this->hyphenator->hyphenate($word);
        }
        return $hyphenatedData;
    }

    private function getDataFromFile(string $filePath): array
    {
        return $this->fileReader->readFile($filePath);
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