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
    private HyphenationInterface $hyphenation;
    private FileHyphenation $fileHyphenation;
    private SentenceHyphenation $sentenceHyphenation;
    private FileExportService $fileExportService;
    private Logger $logger;
    private Timer $timer;
    private Migration $migration;
    private InvokerFormer $invokerFormer;
    private array $patterns;

    public function __construct(
        HyphenationInterface $hyphenation,
        FileHyphenation $fileHyphenation,
        SentenceHyphenation $sentenceHyphenation,
        FileExportService $fileExportService,
        Logger $logger,
        Timer $timer,
        Migration $migration,
        array $patterns
    ) {
        $this->hyphenation = $hyphenation;
        $this->fileHyphenation = $fileHyphenation;
        $this->sentenceHyphenation = $sentenceHyphenation;
        $this->fileExportService = $fileExportService;
        $this->logger = $logger;
        $this->timer = $timer;
        $this->migration = $migration;
        $this->patterns = $patterns;
        $this->invokerFormer = new InvokerFormer(
            $this->hyphenation,
            $this->fileHyphenation,
            $this->sentenceHyphenation,
            $this->migration,
            $this->patterns);

    }

    /**
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function runConsole()
    {
        $this->invokerFormer->validateArguments();
        $this->invokerFormer->validateCommand();
        $this->invokerFormer->validateData();
        $this->runCommand();
    }

    /**
     * @throws Exception
     */
    private function runCommand(): void
    {
        $this->timer->start();
        switch ($this->invokerFormer->getFlag()) {
            case Constants::WORD_COMMAND:
                $invoker = $this->invokerFormer->formWordInvoker();
                break;
            case Constants::SENTENCE_COMMAND:
                $invoker = $this->invokerFormer->formSentenceInvoker();
                break;
            case Constants::FILE_COMMAND:
                $invoker = $this->invokerFormer->formFileInvoker();
                break;
            case Constants::MIGRATE_COMMAND:
                $invoker = $this->invokerFormer->formMigrationInvoker();
                break;
            case Constants::IMPORT_PATTERNS_COMMAND:
                $invoker = $this->invokerFormer->formImportPatternsInvoker();
                break;
        }
        $this->timer->finish();
        $executionTime = $this->timer->getTime();
        $this->logger->info("Hyphenation Algorithm is finished in [$executionTime] seconds");
        $this->fileExportService->exportFile($invoker->handle());
        $this->logger->info("The data has been printed into the file!");
    }
}