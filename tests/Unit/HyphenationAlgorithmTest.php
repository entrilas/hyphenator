<?php

declare(strict_types=1);

use Hyphenation\Algorithm\HyphenationTrie;
use PHPUnit\Framework\TestCase;

class HyphenationAlgorithmTest extends TestCase
{
    private HyphenationTrie $hyphenationTrie;
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

    public function formTestHyphenationProvider(): array
    {
        return [
            ['computer', 'com-put-er'],
            ['miscalculated', 'mis-cal-cu-lat-ed'],
            ['fantastic', 'fan-tas-tic'],
        ];
    }
}