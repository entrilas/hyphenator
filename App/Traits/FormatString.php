<?php

declare(strict_types=1);

namespace App\Traits;

trait FormatString
{
    public function removeNumbers(string $word): string
    {
        return preg_replace('/\d/', '', $word);
    }

    public function clearString(string $word): string
    {
        $word = $this->removeSymbols($word);
        $word = $this->removeNumbers($word);
        return $this->removeSpaces($word);
    }

    public function removeSpaces(string $word): string
    {
        return preg_replace('/\s+/', '', $word);
    }

    public function splitSentenceIntoWords(string $sentence): array
    {
        return preg_split('/\s+/', $sentence, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function removeSymbols(string $word): string
    {
        return trim($word);
    }

    public function validateEmail(string $word): bool
    {
        return preg_match($word, '\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6}');
    }
}
