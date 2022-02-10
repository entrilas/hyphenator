<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;

class SentenceHyphenation extends HyphenationTree
{
    private $hyphenator;

    public function __construct(HyphenationInterface $hyphenator)
    {
        $this->hyphenator = $hyphenator;
    }

    public function hyphenateSentence($sentence)
    {

    }
}