<?php

namespace App\Console\Commands;

use App\Algorithm\FileHyphenation;
use App\Console\Interfaces\CommandInterface;

class FileCommand implements CommandInterface
{
    private FileHyphenation $hyphenator;
    private string $filePath;

    public function __construct(FileHyphenation $hyphenator, $filePath)
    {
        $this->hyphenator = $hyphenator;
        $this->filePath = $filePath;
    }

    public function execute(): array
    {
        return $this->hyphenator->hyphenateFile($this->filePath);
    }
}