<?php

namespace App\Traits;

trait FormatString
{
    public function removeNumbers($word)
    {
        return preg_replace('/\d/', '', $word);
    }

    public function clearString($word)
    {
        $word = $this->removeSymbols($word);
        $word = $this->removeNumbers($word);
        $word = $this->removeSpaces($word);
        return $word;
    }

    public function countDigits($word)
    {
        return preg_match_all("/[0-9]/", $word);
    }

    public function countLetters($word)
    {
        return strlen(preg_replace("/[^a-zA-Z]+/", "", $word));
    }

    public function removeSpaces($word)
    {
        return preg_replace('/\s+/', '', $word);
    }

    public function removeSymbols($word)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $word);
    }
}