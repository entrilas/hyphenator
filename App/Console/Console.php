<?php

declare(strict_types=1);

namespace App\Console;

use App\Algorithm\Hyphenation;
use App\Algorithm\FileHyphenation;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\PatternCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Core\Config;
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
    public function __construct(
        private Config $config,
        private Hyphenation $hyphenation,
        private FileHyphenation $fileHyphenation,
        private SentenceHyphenation $sentenceHyphenation,
        private FileExportService $fileExportService,
        private Import $importService,
        private Export $exportService,
        private Logger $logger,
        private Timer $timer,
        private Migration $migration,
        private Validator $validator
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function runConsole(): void
    {
        $this->validator->validateArguments();
        $this->validator->validateData();
        $this->runCommand();
    }

    /**
     * @throws Exception
     */
    private function runCommand(): void
    {
        $this->timer->start();
        $invoker = match ($this->validator->getFlag()) {
            WordCommand::getCommand() => $this->formWordInvoker(),
            SentenceCommand::getCommand() => $this->formSentenceInvoker(),
            FileCommand::getCommand() => $this->formFileInvoker(),
            MigrationCommand::getCommand() => $this->formMigrationInvoker(),
            PatternCommand::getCommand() => $this->formImportPatternsInvoker(),
            default => throw new InvalidArgumentException(
                "Command was not found!"
            ),
        };
        $this->fileExportService->exportFile($invoker->handle());
        $this->timer->finish();
        $executionTime = $this->timer->getTime();
        $this->logger->info(sprintf("Process is finished in %s seconds", $executionTime));
        $this->logger->info("Process has been finished!");
    }
    public function formImportPatternsInvoker(): CommandInvoker
    {
        return new CommandInvoker(
            new PatternCommand(
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
                $this->migration,
                $this->validator->getData()
            )
        );
    }
}
