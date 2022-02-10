<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Console\Interfaces\CommandInterface;

class WordCommand implements CommandInterface
{
    private HyphenationInterface $hyphenator;
    private string $word;

    public function __construct(HyphenationInterface $hyphenator, $word)
    {
        $this->hyphenator = $hyphenator;
        $this->word = $word;
    }

    public function execute(): string
    {
        return $this->hyphenator->hyphenate($this->word);
    }
}