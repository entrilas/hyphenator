<?php

namespace App\Controllers\API;

use App\Models\Pattern;

class PatternController
{
    public function __construct(
        private Pattern $pattern
    ) {

    }

    public function showAll(): bool|string
    {
        return $this->pattern->getPatterns();
    }

    public function show(array $params = []): bool|string
    {
        return $this->pattern->getPattern($params[0]);
    }
}