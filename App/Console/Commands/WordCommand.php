<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Console\Interfaces\CommandInterface;
use App\Constants\Constants;

class WordCommand implements CommandInterface
{
    public function __construct(
        private HyphenationInterface $hyphenator,
        private string $word
    ) {
    }

    public function execute(): string
    {
        return $this->hyphenator->hyphenate($this->word);
    }

    public static function getCommand(): string
    {
        return Constants::WORD_COMMAND;
    }
}