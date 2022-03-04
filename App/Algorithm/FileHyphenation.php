<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Core\Exceptions\FileNotFoundException;
use App\Services\FileReaderService;
use Exception;

class FileHyphenation
{
    public function __construct(
        private Hyphenation $hyphenator,
        private FileReaderService $fileReader
    ) {
    }

    /**
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function hyphenateFile(string $filePath): array
    {
        $fileData = $this->fileReader->readFile($filePath);
        $hyphenatedData = [];
        foreach ($fileData as $word) {
            $wordModel = $this->hyphenator->hyphenate($word);
            $hyphenatedData[] = $wordModel->getHyphenatedWord();
        }
        return $hyphenatedData;
    }
}
