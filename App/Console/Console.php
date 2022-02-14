<?php

declare(strict_types=1);

namespace App\Console;

use App\Algorithm\FileHyphenation;
use App\Algorithm\Interfaces\HyphenationInterface;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Constants\Constants;
use App\Core\Database\Migration;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Log\Logger;
use App\Core\Timer;
use App\Services\FileExportService;
use Exception;

class Console
{
    private mixed $argc;
    private mixed $argv;
    private HyphenationInterface $hyphenation;
    private FileHyphenation $fileHyphenation;
    private SentenceHyphenation $sentenceHyphenation;
    private FileExportService $fileExportService;
    private Logger $logger;
    private Timer $timer;
    private Migration $migration;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
        FileExportService $fileExportService,
        Logger $logger,
        Timer $timer,
        Migration $migration
    ) {
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
        $this->hyphenation = $hyphenation;
        $this->fileHyphenation = $fileHyphenation;
        $this->sentenceHyphenation = $sentenceHyphenation;
        $this->fileExportService = $fileExportService;
        $this->logger = $logger;
        $this->timer = $timer;
        $this->migration = $migration;
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function runConsole()
    {
        $this->validateArguments();
        $this->validateCommand();
        $this->validateData();
        $this->runCommand();
    }

    /**
     * @throws Exception
     */
    private function runCommand(): void
    {
        $this->timer->start();
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
            case Constants::MIGRATE_COMMAND:
                $invoker = $this->formMigrationInvoker();
                break;
        }
        $this->timer->finish();
        $executionTime = $this->timer->getTime();
        $this->logger->info("Hyphenation Algorithm is finished in [$executionTime] seconds");
        $this->fileExportService->exportFile($invoker->handle());
        $this->logger->info("The data has been printed into the file!");
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

    private function formMigrationInvoker(): CommandInvoker
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
    private function validateCommand(): void
    {
        if ($this->getFlag() == Constants::FILE_COMMAND ||
            $this->getFlag() == Constants::SENTENCE_COMMAND ||
            $this->getFlag() == Constants::WORD_COMMAND ||
            $this->getFlag() == Constants::MIGRATE_COMMAND
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
        if(($this->getData() == null || $this->getData() == '')
            && $this->getFlag() != Constants::MIGRATE_COMMAND)
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
        if(isset($this->argv[1]))
            return $this->argv[1];
        return null;
    }

    private function getData() : mixed
    {
        if(isset($this->argv[2]))
            return $this->argv[2];
        return null;
    }
}