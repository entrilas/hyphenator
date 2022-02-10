<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\FileHyphenation;
use App\Console\Interfaces\CommandInterface;
use App\Core\Exceptions\FileNotFoundException;

class FileCommand implements CommandInterface
{
    private FileHyphenation $hyphenator;
    private string $filePath;

    public function __construct(FileHyphenation $hyphenator, $filePath)
    {
        $this->hyphenator = $hyphenator;
        $this->filePath = $filePath;
    }

    /**
     * @throws FileNotFoundException
     */
    public function execute(): array
    {
        return $this->hyphenator->hyphenateFile($this->filePath);
    }
}