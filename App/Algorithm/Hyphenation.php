<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Console\Logger;
use App\Models\Word;
use App\Traits\FormatString;

class Hyphenation implements HyphenationInterface
{
    use FormatString;

    private array $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function hyphenate($word) : string
    {
        $correctPatterns = $this->findCorrectPatterns($word);
        $breakpoints = $this->findBreakpoints($correctPatterns);
        return $this->insertHyphen('-', $word, $breakpoints);
    }

    private function findCorrectPatterns(string $word) : array
    {
        $correctPatterns = [];
        $patterns = $this->patterns;
        foreach ($patterns as $pattern) {
            $clearedPattern = $this->clearString($pattern);
            $clearedPatternPosition = strpos($word, $clearedPattern);
            if ($this->positionChecker($pattern, $clearedPattern, $clearedPatternPosition, $word)){
                continue;
            }
            $correctPatterns[] = $this->formCorrectPattern($pattern, $clearedPatternPosition);
        }
        return $correctPatterns;
    }

    private function formCorrectPattern(string $pattern, int $patternStartPosition) : array
    {
        preg_match_all('/[0-9]+/', $pattern, $patternOffsets, PREG_OFFSET_CAPTURE);
        return array('pattern' => $pattern,
            'patternOffsets' => $patternOffsets[0],
            'startPosition' => $patternStartPosition);
    }

    private function positionChecker(
        string $pattern,
        string $clearedPattern,
        int $patternPosition,
        string $word
    ) : bool {
        //Not sure yet if all the rules are applied, will read TeX algorithm documentation later
        //First rule checks if position is not found
        //Second rule checks if pattern does not start at the beginning (if pattern contains dot at the start)
        //Third rule checks if pattern applies to the end of the word (if pattern contains dot at the end)
        if ($patternPosition === false || ($pattern[0] == '.' && $patternPosition !== 0) ||
            ($pattern[strlen($pattern) - 1] == '.' && $patternPosition !== strlen($word) - strlen($clearedPattern))){
            return true;
        }
        else{
            return false;
        }
    }

    private function findBreakpoints(array $patterns) : array
    {
        $breakpoints = array();
        foreach ($patterns as $pattern) {
            foreach ($pattern['patternOffsets'] as $key => $offset) {
                $realPosition = $pattern['startPosition'] + $offset[1] - $key;
                $this->setBreakpoint($realPosition, $offset[0], $breakpoints);
            }
        }
        return $breakpoints;
    }

    private function setBreakpoint(int $position, string $value, array &$breakpoints) : void
    {
        if(isset($breakpoints[$position])){
            $breakpoints[$position] = max($breakpoints[$position], $value);
        }
        else{
            $breakpoints[$position] = $value;
        }
    }

    private function insertHyphen(string $hyphen, string $word, array $breakpoints) : array
    {
        $hyphenatedWord = '';
        $chars = str_split($word);
        for ($i = 0; $i < strlen($word); $i++) {
            if (isset($breakpoints[$i])){
                if ($breakpoints[$i] % 2 != 0 && $i > 0){
                    $hyphenatedWord .= $hyphen;
                }
                $hyphenatedWord .= $chars[$i];
            }
        }
        return $hyphenatedWord;
    }
}