<?php

namespace App\Traits;

trait FormatString
{
    function removeNumbers($word)
    {
        return preg_replace('/\d/', '', $word);
    }

    function clearString($word)
    {
        $word = $this->removeSymbols($word);
        $word = $this->removeNumbers($word);
        $word = $this->removeSpaces($word);
        return $word;
    }

    function countDigits($word)
    {
        return preg_match_all("/[0-9]/", $word);
    }

    function countLetters($word)
    {
        return strlen(preg_replace("/[^a-zA-Z]+/", "", $word));
    }

    function removeSpaces($word)
    {
        return preg_replace('/\s+/', '', $word);
    }

    function removeSymbols($word)
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $word);
    }
}