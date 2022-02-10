<?php

declare(strict_types=1);

namespace App\Console;

use App\Algorithm\FileHyphenation;
use App\Algorithm\Interfaces\HyphenationInterface;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Constants\Constants;
use App\Core\Exceptions\InvalidArgumentException;

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

    /**
     * @throws InvalidArgumentException
     */
    public function runConsole()
    {
        $this->validateArguments();
        $this->validateData();
        $this->validateCommand();
        $this->runCommand();
    }

    private function runCommand(): void
    {
        switch ($this->getFlag()) {
            case Constants::WORD_COMMAND:
                $invoker = $this->formWordInvoker();
                break;
            case Constants::SENTENCE_COMMAND:
                $invoker = $this->formSentenceInvoker();
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

    private function formSentenceInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new SentenceCommand(
                $this->sentenceHyphenation,
                $this->getData()
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateCommand(): void
    {
        if ($this->getFlag() == Constants::FILE_COMMAND ||
            $this->getFlag() == Constants::SENTENCE_COMMAND ||
            $this->getFlag() == Constants::WORD_COMMAND
        ) {
        }else{
            throw new InvalidArgumentException("Command does not exist 
            (php index.php [flag] '[content]')");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateData() : void
    {
        if($this->getData() == null || $this->getData() == '')
            throw new InvalidArgumentException("Data provided is null or empty");
    }

    /**
     * @throws InvalidArgumentException
     */
    private function validateArguments() : void
    {
        if($this->argc > 3)
            throw new InvalidArgumentException("Too much arguments provided 
            (php index.php [flag] '[content]')");
    }

    private function getFlag() : mixed
    {
        return $this->argv[1];
    }

    private function getData() : mixed
    {
        return $this->argv[2];
    }
}