<?php

declare(strict_types=1);

namespace App\Models;

class Pattern
{
    public function __construct(
        private int $id = -1,
        private string $pattern,
    ) {
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
