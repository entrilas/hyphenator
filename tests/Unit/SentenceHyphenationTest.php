<?php

declare(strict_types=1);

use App\Algorithm\Hyphenation;
use App\Algorithm\SentenceHyphenation;
use PHPUnit\Framework\TestCase;

class SentenceHyphenationTest extends TestCase
{
    private SentenceHyphenation $sentenceHyphenation;
    private const RESULT = 'com-put-er is the best prop-er-ty';
    private const INPUT = 'computer is the best property';
    private const METHOD = 'hyphenate';

    protected function setUp(): void
    {
        $hyphenation = $this->createMock(
            Hyphenation::class
        );

        $map = $this->formHyphenationMap();

        $hyphenation->expects($this->any())
            ->method(self::METHOD)
            ->will($this->returnValueMap($map));

        $this->sentenceHyphenation = new SentenceHyphenation($hyphenation);
    }

    public function formHyphenationMap(): array
    {
        return [
            ['computer', 'com-put-er'],
            ['is', 'is'],
            ['the', 'best'],
            ['property', 'prop-er-ty'],
        ];
    }

    /**
     * @throws Exception
     */
    public function testSentenceHyphenation(): void
    {
        $result = $this->sentenceHyphenation->hyphenateSentence(self::INPUT);
        $this->assertEquals(self::RESULT, $result);
    }
}