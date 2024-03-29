<?php

declare(strict_types=1);

namespace App\Models;

class ValidPattern
{
    public function __construct(
        private ?int $id,
        private int $fk_word_id,
        private int $fk_pattern_id
    ) {
    }

    /**
     * @return int
     */
    public function getForeignWordId(): int
    {
        return $this->fk_word_id;
    }

    /**
     * @return int
     */
    public function getForeignPatternId(): int
    {
        return $this->fk_pattern_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
