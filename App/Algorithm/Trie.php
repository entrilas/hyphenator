<?php

declare(strict_types=1);

namespace App\Algorithm;

use App\Traits\FormatString;

class Trie
{
    use FormatString;

    private mixed $trie;

    public function insert($pattern): void
    {
        $trie = &$this->trie;
        $pattern = $this->removeSymbols($pattern);
        $clearPattern = $this->removeNumbers($pattern);
        $node = &$trie;
        $this->insertAllCharacters($pattern, $clearPattern, $node);
    }

    private function insertAllCharacters(
        string $pattern,
        string $clearPattern,
        array &$node = null
    ): void {
        foreach (str_split($clearPattern) as $char) {
            if (!isset($node[$char])) {
                $node[$char] = array();
            }
            $node = &$node[$char];
        }
        $node['patternName'] = $this->formPatternData($pattern);
    }

    private function formPatternData(string $pattern): array
    {
        preg_match_all('/([0-9]+)/', $pattern, $offsetsData, PREG_OFFSET_CAPTURE);
        return array(
            'pattern' => $pattern,
            'offsets' => $offsetsData[1]
        );
    }

    public function getTrie(): mixed
    {
        return $this->trie;
    }

}