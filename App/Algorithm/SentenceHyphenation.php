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
        foreach ($wordsArray as $i => $word) {
            if (strlen($word) > Constants::MINIMUM_WORD_LENGTH) {
                $wordModel = $this->hyphenator->hyphenate($word);
                $wordsArray[$i] = sprintf("%s", $wordModel->getHyphenatedWord());
            } else {
                $wordsArray[$i] = sprintf("%s", $word);
            }
        }
        return implode(" ", $wordsArray);
    }
}
