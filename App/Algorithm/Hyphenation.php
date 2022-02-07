<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Traits\FormatString;

class Hyphenation implements HyphenationInterface
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
        $this->findBreakpoints($correctPatterns);
        return $this->insertHyphen('-', $word);
    }

    private function findCorrectPatterns($word)
    {
        $correctPatterns = [];
        $patterns = $this->patterns;
        foreach ($patterns as $pattern) {
            $clearedPattern = $this->clearString($pattern);
            $clearedPatternPosition = strpos($word, $clearedPattern);
            if ($this->positionChecker($pattern, $clearedPattern, $clearedPatternPosition, $word))
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

    private function findBreakpoints($patterns)
    {
        foreach ($patterns as $pattern) {
            foreach ($pattern['patternOffsets'] as $key => $offset) {
                $realPosition = $pattern['startPosition'] + $offset[1] - $key;
                $this->setBreakpoint($realPosition, $offset[0]);
            }
        }
    }

    private function setBreakpoint($position, $value)
    {
        $breakpoints = &$this->breakpoints;
        if (!isset($breakpoints[$position]))
            $breakpoints[$position] = $value;
        else if ($breakpoints[$position] <= $value)
            $breakpoints[$position] = $value;
    }

    private function insertHyphen($hyphen, $word)
    {
        $hyphenatedWord = '';
        $chars = str_split($word);
        for ($i = 0; $i < strlen($word); $i++) {
            if (isset($this->breakpoints[$i]))
                if ($this->breakpoints[$i] % 2 != 0 && $i > 0)
                    $hyphenatedWord .= $hyphen;
            $hyphenatedWord .= $chars[$i];
        }
        return $hyphenatedWord;
    }
}