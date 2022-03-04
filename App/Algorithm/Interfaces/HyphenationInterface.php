<?php

namespace App\Algorithm\Interfaces;

use App\Models\Word;

interface HyphenationInterface
{
    public function hyphenate(string $word): Word;
    public function getValidPatterns(): array;
}
