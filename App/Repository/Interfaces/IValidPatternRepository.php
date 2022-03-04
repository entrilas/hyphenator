<?php

declare(strict_types=1);

namespace App\Repository\Interfaces;

interface IValidPatternRepository
{
    public function submitValidPattern(int $fk_word_id, int $fk_pattern_id): bool;
}
