<?php

include_once 'FormatString.php';

class Hyphenator
{
    use FormatString;

    private $patterns;
    private $breakpoints;

    public function __construct($patterns)
    {
        $this->patterns = $patterns;
    }

    public function hyphenate($word)
    {
        $correctPatterns = $this->findCorrectPatterns($word);
        $this->findBreakpoints($word, $correctPatterns);
        print_r($this->breakpoints);
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
        //Second rule checks if pattern does not start at the beginning (if pattern contains dot at the start)
        //Third rule checks if pattern applies to the end of the word (if pattern contains dot at the end)
        if ($patternPosition === false || ($pattern[0] == '.' && $patternPosition !== 0) ||
            ($pattern[strlen($pattern) - 1] == '.' && $patternPosition !== strlen($word) - strlen($clearedPattern)))
            return true;
        else
            return false;
    }

    private function findBreakpoints($word, $patterns)
    {
        foreach($patterns as $pattern)
        {
            foreach($pattern['patternOffsets'] as $key => $offset)
            {
                $realPosition = $pattern['startPosition'] + $offset[1] - $key;
                $this->setBreakpoint($realPosition, $offset[0]);
            }
        }
    }

    private function setBreakpoint($position, $value)
    {
        $breakpoints = &$this->breakpoints;
        if(!isset($breakpoints[$position]))
            $breakpoints[$position] = $value;
    }
}