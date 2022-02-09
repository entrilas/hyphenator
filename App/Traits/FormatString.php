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
        $word = $this->removeSpaces($word);
        return $word;
    }

    public function removeSpaces(string $word) : string|null
    {
        return preg_replace('/\s+/', '', $word);
    }

    public function removeSymbols(string $word) : string
    {
        return trim($word);
    }
}
