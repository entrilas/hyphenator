<?php

namespace App\Controllers\API;

use App\Models\Pattern;
use App\Models\Word;

class PatternController
{
    public function __construct(
        private Pattern $pattern
    ) {

    }

    public function showAll()
    {
        return $this->pattern->getPatterns();
    }
}