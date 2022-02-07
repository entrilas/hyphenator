<?php

include_once 'FormatString.php';

class Hyphenator
{
    use FormatString;

    private $patterns;

    public function __construct($patterns)
    {
        $this->patterns = $patterns;
    }

    public function hyphenate($word)
    {
        $correctPatterns = $this->findCorrectPatterns($word);
        print_r($correctPatterns);
    }

    private function findCorrectPatterns($word)
    {
        $correctPatterns = [];
        $patterns = $this->patterns;
        foreach($patterns as $pattern)
        {
            $clearedPattern = $this->clearString($pattern);
            $clearedPatternPosition = strpos($word, $clearedPattern);
            if($this->positionChecker($pattern, $clearedPattern, $clearedPatternPosition, $word))
                continue;
            $correctPatterns[] = $this->formCorrectPattern($pattern, $clearedPatternPosition);
        }
        return $correctPatterns;
    }

    private function formCorrectPattern($pattern, $patternStartPosition)
    {
        preg_match_all('/[0-9]+/', $pattern, $patternOffsets, PREG_OFFSET_CAPTURE);
        return array('pattern' => $pattern,
                    'patternOffsets' => $patternOffsets[0],
                    'startPosition' => $patternStartPosition);
    }

    private function positionChecker($pattern, $clearedPattern, $patternPosition, $word)
    {
        //Not sure yet if all the rules are applied, will read TeX algorithm documentation later
        //First rule checks if position is not found
        //Second rule checks if pattern does not start at the beggining (if pattern contains dot at the start)
        //Third rule checks if pattern applies to the end of the word (if pattern contains dot at the end)
        if ($patternPosition === false || ($pattern[0] == '.' && $patternPosition !== 0) ||
            ($pattern[strlen($pattern) - 1] == '.' && $patternPosition !== strlen($word) - strlen($clearedPattern)))
            return true;
        else
            return false;
    }
}