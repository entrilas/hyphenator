<?php

namespace App\Controllers\API;

use App\Models\Word;

class WordController
{
    public function __construct(
        private Word $word
    ) {

    }

    public function showAll()
    {
        return $this->word->getWords();
    }
}