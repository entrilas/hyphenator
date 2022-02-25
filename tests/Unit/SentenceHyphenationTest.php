<?php

use App\Algorithm\Hyphenation;
use App\Algorithm\SentenceHyphenation;
use PHPUnit\Framework\TestCase;

class SentenceHyphenationTest extends TestCase
{
    private SentenceHyphenation $sentenceHyphenation;

    protected function setUp(): void
    {
        $this->sentenceHyphenation = $this->createMock(
            SentenceHyphenation::class
        );

        $this->sentenceHyphenation->method('hyphenateSentence')
            ->willReturn('com-put-er is the best prop-er-ty');
    }

    /**
     * @throws Exception
     */
    public function testSentenceHyphenation(): void
    {

        $result = $this->sentenceHyphenation->hyphenateSentence('com-put-er is the best prop-er-ty');
        $this->assertEquals('com-put-er is the best prop-er-ty', $result);
    }
}