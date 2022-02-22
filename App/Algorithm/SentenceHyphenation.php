<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Traits\FormatString;
use Exception;

class SentenceHyphenation
{
    use FormatString;

    public function __construct(
        private Hyphenation $hyphenator
    ) {
    }

    /**
     * @throws Exception
     */
    public function hyphenateSentence(string $sentence): string
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
