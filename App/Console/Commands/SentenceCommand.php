<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\SentenceHyphenation;
use App\Console\Interfaces\CommandInterface;
use App\Constants\Constants;
use Exception;

class SentenceCommand implements CommandInterface
{
    public function __construct(
        private SentenceHyphenation $hyphenator,
        private string $filePath
    ) {
    }

    /**
     * @throws Exception
     */
    public function execute(): string
    {
        return $this->hyphenator->hyphenateSentence($this->filePath);
    }

    public static function getCommand(): string
    {
        return Constants::SENTENCE_COMMAND;
    }
}