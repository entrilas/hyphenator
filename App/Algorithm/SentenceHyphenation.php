<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Constants\Constants;
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
            if(strlen($word) > Constants::MINIMUM_WORD_LENGTH){
                $hyphenatedSentence .= sprintf("%s ", $this->hyphenator->hyphenate($word));
            }else
                $hyphenatedSentence .= sprintf("%s ", $word);
        }
        return $hyphenatedSentence;
    }
}
