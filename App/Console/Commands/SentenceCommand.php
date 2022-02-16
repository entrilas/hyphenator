<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\SentenceHyphenation;
use App\Console\Interfaces\CommandInterface;

class SentenceCommand implements CommandInterface
{
    public function __construct(
        private SentenceHyphenation $hyphenator,
        private string $filePath
    ) {
    }

    public function execute(): string
    {
        return $this->hyphenator->hyphenateSentence($this->filePath);
    }
}