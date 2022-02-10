<?php

declare(strict_types=1);

namespace App\Traits;

trait FormatString
{
    public function removeNumbers(string $word): string|null
    {
        return preg_replace('/\d/', '', $word);
    }

    public function clearString(string $word) : string
    {
        $word = $this->removeSymbols($word);
        $word = $this->removeNumbers($word);
        return $this->removeSpaces($word);
    }

    public function removeSpaces(string $word) : string|null
    {
        return preg_replace('/\s+/', '', $word);
    }

    public function splitSentenceIntoWords(string $sentence) : array
    {
        return preg_split('/\s+/', $sentence, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function removeSymbols(string $word) : string
    {
        return trim($word);
    }
}
