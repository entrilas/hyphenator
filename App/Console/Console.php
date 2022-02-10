<?php

namespace App\Console;

use App\Algorithm\FileHyphenation;
use App\Algorithm\Interfaces\HyphenationInterface;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\WordCommand;
use App\Constants\Constants;

class Console
{
    private mixed $argc;
    private mixed $argv;
    private HyphenationInterface $hyphenation;
    private FileHyphenation $fileHyphenation;
    private SentenceHyphenation $sentenceHyphenation;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
    ) {
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
        $this->hyphenation = $hyphenation;
        $this->fileHyphenation = $fileHyphenation;
        $this->sentenceHyphenation = $sentenceHyphenation;
    }

    public function runConsole()
    {
        $this->runCommand();
    }

    private function runCommand()
    {
        switch ($this->getFlag()) {
            case Constants::WORD_COMMAND:
                $invoker = $this->formWordInvoker();
                break;
            case Constants::SENTENCE_COMMAND:
                echo "TEST2";
                break;
            case Constants::FILE_COMMAND:
                $invoker = $this->formFileInvoker();
                break;
        }
        print_r($invoker->handle());
    }

    private function formWordInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new WordCommand(
                $this->hyphenation,
                $this->getData()
            )
        );
    }

    private function formFileInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new FileCommand(
                $this->fileHyphenation,
                $this->getData()
            )
        );
    }

    private function getFlag()
    {
        return $this->argv[1];
    }

    private function getData()
    {
        return $this->argv[2];
    }
}