<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Log\Logger;
use App\Models\Word;
use App\Traits\FormatString;

class HyphenationTree implements HyphenationInterface
{
    use FormatString;

    private $patterns;
    private $patternTrie;
    private $logger;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
        $this->formPatternTrie();
        $this->logger = Logger::getInstance();
    }

    public function hyphenate(string $word) : string
    {
        Logger::info("Algorithm started for word {$word}");
        $breakpoints = $this->findBreakpoints($word);
        return $this->insertHyphen($word, $breakpoints);
    }

    private function findBreakpoints(string $word) : array
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
                    $this->logger->info("Found pattern for word {$word} : {$node['patternName']['pattern']}");
                }
                if (!isset($node[$chars[$step]])) {
                    break;
                }
                $node = &$node[$chars[$step]];
            }
        }
        return $breakpoints;
    }

    private function findMaxBreakpointValue(
        array $node,
        int $start,
        array &$breakpoints
    ) : void {
        foreach ($node['patternName']['offsets'] as $offsetIndex => $patternOffset) {
            $value  = $patternOffset[0];
            $offset = $patternOffset[1] + $start - $offsetIndex;
            if(isset($breakpoints[$offset])){
                $breakpoints[$offset] = max($breakpoints[$offset], $value);
            }else{
                $breakpoints[$offset] = $value;
            }
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
    private function insertAllCharacters(
        string $pattern,
        string $clearPattern,
        array &$node = null
    ) : void {
        foreach (str_split($clearPattern) as $char) {
            if (!isset($node[$char])) {
                $node[$char] = array();
            }
            $node = &$node[$char];
        }
        $node['patternName'] = $this->formPatternData($pattern);
    }

    private function formPatternData(string $pattern) : array
    {
        preg_match_all('/([0-9]+)/', $pattern, $offsetsData, PREG_OFFSET_CAPTURE);
        return array(
            'pattern' => $pattern,
            'offsets' => $offsetsData[1]
        );
    }

    private function insertHyphen(string $word, array $breakpoints) : string
    {
        krsort($breakpoints);
        $hyphenatedWord = $word;
        foreach($breakpoints as $offset => $value){
            if(($value % 2 != 0) && $offset > 1){
                $hyphenatedWord = substr_replace($hyphenatedWord, '-', $offset-1, 0);
            }
        }
        $this->logger->info("Word {$word} was hyphenated : {$hyphenatedWord}");
        return $hyphenatedWord;
    }
}

