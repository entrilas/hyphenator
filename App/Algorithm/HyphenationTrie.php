<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Models\Word;
use App\Traits\FormatString;

class HyphenationTrie implements HyphenationInterface
{
    use FormatString;

    private $patterns;
    private $patternTrie;

    public function __construct($patterns)
    {
        $this->patterns = $patterns;
        $this->formPatternTrie();
    }

    public function hyphenate($word): string
    {
        $breakpoints = $this->findBreakpoints($word);
        return $this->insertHyphen($word, $breakpoints);
    }

    private function findBreakpoints($word)
    {
        $word   = '.'.$word.'.';
        $chars  = str_split(strtolower($word));
        $charLength = sizeof($chars);

        $breakpoints = array();

        for ($start = 0; $start < $charLength; $start++) {
            $node = &$this->patternTrie;
            for ($step = $start; $step < $charLength; $step++) {
                if (isset($node['patternName'])) {
                    $this->findMaxBreakpointValue($node, $start, $breakpoints);
                }
                if (!isset($node[$chars[$step]])) {
                    break;
                }
                $node = &$node[$chars[$step]];
            }
        }
        return $breakpoints;
    }

    private function findMaxBreakpointValue($node, $start, &$breakpoints)
    {
        foreach ($node['patternName']['offsets'] as $offsetIndex => $patternOffset) {
            $value  = $patternOffset[0];
            $offset = $patternOffset[1] + $start - $offsetIndex;
            if(isset($breakpoints[$offset]))
                $breakpoints[$offset] = max($breakpoints[$offset], $value);
            else
                $breakpoints[$offset] = $value;
        }
    }

    private function formPatternTrie()
    {
        $trie = &$this->patternTrie;
        foreach ($this->patterns as $pattern) {
            $pattern = $this->removeSymbols($pattern);
            $clearPattern = $this->removeNumbers($pattern);
            $this->patterns[$clearPattern] = $pattern;
            $node = &$trie;
            $this->insertAllCharacters($pattern, $clearPattern, $node);
        }
    }
    private function insertAllCharacters($pattern, $clearPattern, &$node)
    {
        foreach (str_split($clearPattern) as $char) {
            if (!isset($node[$char])) {
                $node[$char] = array();
            }
            $node = &$node[$char];
        }
        $node['patternName'] = $this->formPatternData($pattern);
    }

    private function formPatternData($pattern)
    {
        preg_match_all('/([0-9]+)/', $pattern, $offsetsData, PREG_OFFSET_CAPTURE);
        return array(
            'pattern' => $pattern,
            'offsets' => $offsetsData[1]
        );
    }

    private function insertHyphen($word, $breakpoints)
    {
        krsort($breakpoints);
        $hyphenatedWord = $word;
        foreach($breakpoints as $offset => $value)
            if(($value % 2 != 0) && $offset > 1)
                $hyphenatedWord = substr_replace($hyphenatedWord, '-', $offset-1, 0);
        return $hyphenatedWord;
    }
}