<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\FileHyphenation;
use App\Console\Interfaces\CommandInterface;
use App\Core\Exceptions\FileNotFoundException;

class FileCommand implements CommandInterface
{
    public function __construct(
        private FileHyphenation $hyphenator,
        private string $filePath
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function execute(): array
    {
        return $this->hyphenator->hyphenateFile($this->filePath);
    }
}