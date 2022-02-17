<?php

namespace App\Controllers\API;

use App\Models\Word;

class WordController
{
    public function __construct(
        private Word $word
    ) {

    }

    public function showAll(): bool|string
    {
        return $this->word->getWords();
    }

    public function show(array $params = []): bool|string
    {
        return $this->word->getWord($params[0]);
    }
}