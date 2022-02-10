<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Exceptions\FileNotFoundException;
use App\Services\FileReader;

class FileHyphenation extends HyphenationTree
{
    private HyphenationInterface $hyphenator;
    private FileReader $fileReader;

    public function __construct(HyphenationInterface $hyphenator, FileReader $fileReader)
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