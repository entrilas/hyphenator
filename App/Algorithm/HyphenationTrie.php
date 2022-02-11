<?php

namespace App\Algorithm;

use App\Algorithm\Interfaces\HyphenationInterface;
use App\Core\Log\Interfaces\LoggerInterface;
use App\Traits\FormatString;

class HyphenationTrie implements HyphenationInterface
{
    use FormatString;

    private array $patterns;
    private mixed $patternTrie;
    private LoggerInterface $logger;

    public function __construct(array $patterns, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->patterns = $patterns;
        $this->patternTrie = new Trie();
        $this->formPatternTrie();
        $this->patternTrie = $this->patternTrie->getTrie();
    }

    public function hyphenate(string $word): string
    {
        $this->logger->info("Algorithm started for word $word");
        $breakpoints = $this->findBreakpoints($word);
        return $this->insertHyphen($word, $breakpoints);
    }

    private function findBreakpoints(string $word): array
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
                    $this->logger->info("Found pattern for word $word : {$node['patternName']['pattern']}");
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
    ): void {
        foreach ($node['patternName']['offsets'] as $offsetIndex => $patternOffset) {
            $value  = $patternOffset[0];
            $offset = $patternOffset[1] + $start - $offsetIndex;
            if (isset($breakpoints[$offset])) {
                $breakpoints[$offset] = max($breakpoints[$offset], $value);
            } else {
                $breakpoints[$offset] = $value;
            }
        }
    }

    private function formPatternTrie(): void
    {
        foreach($this->patterns as $pattern) {
            $this->patternTrie->insert($pattern);
        }
    }

    private function insertHyphen(string $word, array $breakpoints): string
    {
        krsort($breakpoints);
        $hyphenatedWord = $word;
        foreach ($breakpoints as $offset => $value) {
            if (($value % 2 != 0) && $offset > 1) {
                $hyphenatedWord = substr_replace($hyphenatedWord, '-', $offset-1, 0);
            }
        }
        $this->logger->info("Word $word was hyphenated : $hyphenatedWord");
        return $hyphenatedWord;
    }
}
