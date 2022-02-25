<?php

use Hyphenation\Algorithm\HyphenationTrie;
use PHPUnit\Framework\TestCase;

class HyphenationAlgorithmTest extends TestCase
{
    private HyphenationTrie $hyphenationTrie;
    private const RESULT = 'com-put-er';
    private const INPUT = 'computer';
    private const HYPHENATE_METHOD = 'hyphenate';

    protected function setupMockup(string $result): void
    {
        $this->hyphenationTrie = $this->createMock(
            HyphenationTrie::class
        );

        $this->hyphenationTrie->method(self::HYPHENATE_METHOD)
            ->willReturn($result);
    }

    /**
     * @dataProvider formTestHyphenationProvider
     */
    public function testHyphenation(string $input, string $result): void
    {
        $this->setupMockup($result);
        $hyphenatedWord = $this->hyphenationTrie->hyphenate($input);
        $this->assertEquals($result, $hyphenatedWord);
    }

    public function formTestHyphenationProvider()
    {
        return [
            ['computer', 'com-put-er'],
            ['miscalculated', 'mis-cal-cu-lat-ed'],
            ['fantastic', 'fan-tas-tic'],
        ];
    }

}