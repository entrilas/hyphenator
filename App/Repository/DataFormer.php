<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Pattern;
use App\Models\Word;

class DataFormer
{
    /**
     * @param array|bool $patternArray
     * Forms Data for the Pattern Model
     * @return Pattern|bool
     */
    public function formPatternModel(array|bool $patternArray): Pattern|bool
    {
        if ($patternArray !== false) {
            return new Pattern(
                (int)$patternArray['id'],
                $patternArray['pattern']
            );
        }
        return false;
    }

    /**
     * @param array|bool $wordArray
     *
     * @return Word|bool
     */
    public function formWordModel(array|bool $wordArray): Word|bool
    {
        if ($wordArray !== false) {
            return new Word(
                (int)$wordArray['id'],
                $wordArray['word'],
                $wordArray['hyphenated_word']
            );
        }
        return false;
    }
}