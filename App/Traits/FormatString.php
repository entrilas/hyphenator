<?php

namespace App\Traits;

trait FormatString
{
    public function removeNumbers($word){
        return preg_replace('/\d/', '', $word);
    }

    public function clearString($word){
        $word = $this->removeSymbols($word);
        $word = $this->removeNumbers($word);
        $word = $this->removeSpaces($word);
        return $word;
    }

    public function removeSpaces($word){
        return preg_replace('/\s+/', '', $word);
    }

    public function removeSymbols($word){
        return trim($word);
    }
}
