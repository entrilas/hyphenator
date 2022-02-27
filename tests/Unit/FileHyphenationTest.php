<?php

declare(strict_types=1);

use App\Algorithm\FileHyphenation;
use App\Algorithm\Hyphenation;
use App\Services\FileReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileHyphenationTest extends TestCase
{
    private FileHyphenation $fileHyphenation;
    private CONST FILE_PATH = __DIR__ . '/../../resources/copy-file.txt';
    private const METHOD = 'hyphenate';

    protected function setUp(): void
    {
        $fileReaderService = $this->mockFileReader();
        $hyphenation = $this->mockHyphenation();
        $this->fileHyphenation = new FileHyphenation($hyphenation, $fileReaderService);
    }

    private function mockFileReader(): MockObject|FileReaderService
    {
        $fileReaderService = $this->createMock(
            FileReaderService::class
        );

        $fileReaderService
            ->expects($this->any())
            ->method('readFile')
            ->with(self::FILE_PATH)
            ->willReturn($this->createReaderServiceResult());

        return $fileReaderService;
    }

    private function mockHyphenation(): MockObject|Hyphenation
    {
        $hyphenation = $this->createMock(
            Hyphenation::class
        );

        $map = $this->formHyphenationMap();

        $hyphenation->expects($this->any())
            ->method(self::METHOD)
            ->will($this->returnValueMap($map));

        return $hyphenation;
    }

    /**
     * @throws Exception
     */
    public function testFileHyphenation(): void
    {
        $result = $this->fileHyphenation->hyphenateFile(self::FILE_PATH);
        $this->assertEquals($this->createResultMap(), $result);
    }

    private function formHyphenationMap(): array
    {
        return [
            ['barbuts', 'bar-but-s'],
            ['barbwire', 'barb-wire'],
            ['barbwires', 'barb-wires'],
            ['barcan', 'bar-can'],
            ['barcarole', 'bar-carole']
        ];
    }

    private function createResultMap(): array
    {
        return [
            'bar-but-s',
            'barb-wire',
            'barb-wires',
            'bar-can',
            'bar-carole'
        ];
    }

    private function createReaderServiceResult(): array
    {
        return [
            'barbuts',
            'barbwire',
            'barbwires',
            'barcan',
            'barcarole'
        ];
    }
}