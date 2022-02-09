<?php

namespace App\Algorithm\Interfaces;

interface HyphenationInterface
{
    public function hyphenate(string $word) : string;
}
