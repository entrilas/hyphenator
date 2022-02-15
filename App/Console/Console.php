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
use App\Core\Database\Export;
use App\Core\Database\Migration;
use App\Core\Database\Import;
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
    private Import $importService;
    private Export $exportService;
    private Logger $logger;
    private Timer $timer;
    private Migration $migration;
    private array $patterns;
    private Validator $validator;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
        FileExportService $fileExportService,
        Import $patternImportService,
        Export $exportService,
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
        $this->importService = $patternImportService;
        $this->exportService = $exportService;
        $this->logger = $logger;
        $this->timer = $timer;
        $this->migration = $migration;
        $this->patterns = $patterns;
        $this->validator = Validator::getInstanceOf();
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function runConsole(): void
    {
        $this->validator->validateArguments();
        $this->validator->validateCommand();
        $this->validator->validateData();
        $this->runCommand();
    }

    /**
     * @throws Exception
     */
    private function runCommand(): void
    {
        $this->timer->start();
        switch ($this->validator->getFlag()) {
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
                $this->importService,
                $this->validator->getData()
            )
        );
    }

    public function formWordInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new WordCommand(
                $this->hyphenation,
                $this->validator->getData()
            )
        );
    }

    public function formFileInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new FileCommand(
                $this->fileHyphenation,
                $this->validator->getData()
            )
        );
    }

    public function formSentenceInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new SentenceCommand(
                $this->sentenceHyphenation,
                $this->validator->getData()
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
}