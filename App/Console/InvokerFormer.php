<?php

declare(strict_types=1);

namespace App\Console;

use App\Algorithm\FileHyphenation;
use App\Algorithm\Interfaces\HyphenationInterface;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\PatternCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Constants\Constants;
use App\Core\Database\Migration;
use App\Core\Exceptions\InvalidArgumentException;

class InvokerFormer
{
    private HyphenationInterface $hyphenation;
    private FileHyphenation $fileHyphenation;
    private SentenceHyphenation $sentenceHyphenation;
    private Migration $migration;
    private array $patterns;
    private mixed $argv;
    private mixed $argc;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
        Migration $migration,
        array $patterns
    )
    {
        $this->hyphenation = $hyphenation;
        $this->fileHyphenation = $fileHyphenation;
        $this->sentenceHyphenation = $sentenceHyphenation;
        $this->migration = $migration;
        $this->patterns = $patterns;
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
    }

    public function formImportPatternsInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new PatternCommand(
                $this->patterns
            )
        );
    }

    public function formWordInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new WordCommand(
                $this->hyphenation,
                $this->getData()
            )
        );
    }

    public function formFileInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new FileCommand(
                $this->fileHyphenation,
                $this->getData()
            )
        );
    }

    public function formSentenceInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new SentenceCommand(
                $this->sentenceHyphenation,
                $this->getData()
            )
        );
    }

    public function formMigrationInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new MigrationCommand(
                $this->migration
            )
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateCommand(): void
    {
        if ($this->getFlag() == Constants::FILE_COMMAND ||
            $this->getFlag() == Constants::SENTENCE_COMMAND ||
            $this->getFlag() == Constants::WORD_COMMAND ||
            $this->getFlag() == Constants::MIGRATE_COMMAND ||
            $this->getFlag() == Constants::IMPORT_PATTERNS_COMMAND
        ) {
        }else{
            throw new InvalidArgumentException("Command does not exist 
            (php index.php [flag] '[content]')");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateData() : void
    {
        if(($this->getData() == null || $this->getData() == '')
            && $this->getFlag() != Constants::MIGRATE_COMMAND)
            throw new InvalidArgumentException("Data provided is null or empty");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateArguments() : void
    {
        if($this->argc > 3)
            throw new InvalidArgumentException("Too much arguments provided 
            (php index.php [flag] '[content]')");
    }

    public function getFlag() : mixed
    {
        if(isset($this->argv[1]))
            return $this->argv[1];
        return null;
    }

    public function getData() : mixed
    {
        if(isset($this->argv[2]))
            return $this->argv[2];
        return null;
    }
}