<?php

declare(strict_types=1);


namespace App\Models;

class ValidPattern
{
    public function __construct(
        private int $id = -1,
        private int $fk_word_id,
        private int $fk_pattern_id
    ) {
    }

    public function getForeignWordId(): int
    {
        return $this->fk_word_id;
    }

    public function getForeignPatternId(): int
    {
        return $this->fk_pattern_id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
