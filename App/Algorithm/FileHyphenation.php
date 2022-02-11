<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Exceptions\FileNotFoundException;
use App\Services\FileReaderService;

class FileHyphenation
{
    private HyphenationInterface $hyphenator;
    private FileReaderService $fileReader;

    public function __construct(HyphenationInterface $hyphenator, FileReaderService $fileReader)
    {
        $this->hyphenator = $hyphenator;
        $this->fileReader = $fileReader;
    }

    /**
     * @throws FileNotFoundException
     */
    public function hyphenateFile($filePath): array
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

    private function getDataFromFile($filePath): array
    {
        return $this->fileReader->readFile($filePath);
    }

    /**
     * @throws FileNotFoundException
     */
    private function validatePath($filePath)
    {
        $absPath = realpath($filePath);
        if ($absPath === false) {
            throw new FileNotFoundException("File with URL [ $filePath ] was not found.");
        }
    }
}