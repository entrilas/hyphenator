<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Traits\FormatString;

class SentenceHyphenation
{
    use FormatString;

    private $hyphenator;

    public function __construct(HyphenationInterface $hyphenator)
    {
        $this->hyphenator = $hyphenator;
    }

    public function hyphenateSentence($sentence): string
    {
        $wordsArray = $this->splitSentenceIntoWords($sentence);
        $hyphenatedSentence = '';
        foreach($wordsArray as $word)
        {
            if(strlen($word) > 4){
                $hyphenatedSentence .= $this->hyphenator->hyphenate($word) . " ";
            }else
                $hyphenatedSentence .= $word . " ";
        }
        return $hyphenatedSentence;
    }

}