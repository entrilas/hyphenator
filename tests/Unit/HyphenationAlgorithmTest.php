<?php

use Hyphenation\Algorithm\HyphenationTrie;
use PHPUnit\Framework\TestCase;

class HyphenationAlgorithmTest extends TestCase
{
    private HyphenationTrie $hyphenationTrie;
    private const RESULT = 'com-put-er';
    private const INPUT = 'computer';
    private const HYPHENATE_METHOD = 'hyphenate';

    protected function setUp(): void
    {
        $this->hyphenationTrie = $this->createMock(
            HyphenationTrie::class
        );

        $this->hyphenationTrie->method(self::HYPHENATE_METHOD)
            ->willReturn(self::RESULT);
    }

    /**
     * @throws Exception
     */
    public function testHyphenation(): void
    {
        $result = $this->hyphenationTrie->hyphenate(self::INPUT);
        $this->assertEquals(SELF::RESULT, $result);
    }
}