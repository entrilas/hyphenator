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
use App\Core\Database\PatternImport;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Log\Logger;
use App\Core\Timer;
use App\Services\FileExportService;
use Exception;

class Console
{
    private HyphenationInterface $hyphenation;
    private FileHyphenation $fileHyphenation;
    private SentenceHyphenation $sentenceHyphenation;
    private FileExportService $fileExportService;
    private PatternImport $patternImportService;
    private Logger $logger;
    private Timer $timer;
    private Migration $migration;
    private array $patterns;
    private mixed $argv;
    private mixed $argc;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
        FileExportService $fileExportService,
        PatternImport $patternImportService,
        Logger $logger,
        Timer $timer,
        Migration $migration,
        array $patterns
    )
    {
        $this->hyphenation = $hyphenation;
        $this->fileHyphenation = $fileHyphenation;
        $this->sentenceHyphenation = $sentenceHyphenation;
        $this->fileExportService = $fileExportService;
        $this->patternImportService = $patternImportService;
        $this->logger = $logger;
        $this->timer = $timer;
        $this->migration = $migration;
        $this->patterns = $patterns;
        $this->argv = $_SERVER['argv'];
        $this->argc = $_SERVER['argc'];
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function runConsole(): void
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
            case Constants::IMPORT_PATTERNS_COMMAND:
                $invoker = $this->formImportPatternsInvoker();
                break;
        }
        $this->timer->finish();
        $executionTime = $this->timer->getTime();
        $this->logger->info("Process is finished in [$executionTime] seconds");
        $this->fileExportService->exportFile($invoker->handle());
        $this->logger->info("Process has been finished!");
    }
    public function formImportPatternsInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new PatternCommand(
                $this->patterns,
                $this->patternImportService,
                $this->getData()
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
    public function validateData(): void
    {
        if(($this->getData() == null || $this->getData() == '')
            && ($this->getFlag() == Constants::IMPORT_PATTERNS_COMMAND))
            throw new InvalidArgumentException("Data provided is null or empty");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateArguments(): void
    {
        if($this->argc > 3)
            throw new InvalidArgumentException("Invalid arguments provided 
            (php index.php [flag] '[content]')");
    }

    public function getFlag(): mixed
    {
        if(isset($this->argv[1]))
            return $this->argv[1];
        return null;
    }

    public function getData(): mixed
    {
        if(isset($this->argv[2]))
            return $this->argv[2];
        return null;
    }
}