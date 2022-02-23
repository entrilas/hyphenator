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
use App\Constants\Constants;
use App\Core\Config;
use App\Core\Database\Export;
use App\Core\Database\Migration;
use App\Core\Database\Import;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Log\Logger;
use App\Core\Timer;
use App\Services\DataExportService;
use Exception;

class Console
{
    public function __construct(
        private Config $config,
        private Hyphenation $hyphenation,
        private FileHyphenation $fileHyphenation,
        private SentenceHyphenation $sentenceHyphenation,
        private DataExportService $dataExportService,
        private Import $importService,
        private Export $exportService,
        private Logger $logger,
        private Timer $timer,
        private Migration $migration,
        private Validator $validator,
        private InvokerFormer $invokerFormer,
        private Input $inputReceiver
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
        $invoker = match ($this->inputReceiver->getFlag()) {
            WordCommand::getCommand() => $this->invokerFormer->formWordInvoker(),
            SentenceCommand::getCommand() => $this->invokerFormer->formSentenceInvoker(),
            FileCommand::getCommand() => $this->invokerFormer->formFileInvoker(),
            MigrationCommand::getCommand() => $this->invokerFormer->formMigrationInvoker(),
            PatternCommand::getCommand() => $this->invokerFormer->formImportPatternsInvoker(),
            default => throw new InvalidArgumentException(
                "Command was not found!"
            ),
        };
        $this->printData($invoker->handle());
        $this->timer->finish();
        $executionTime = $this->timer->getTime();
        $this->logger->info(sprintf("Process is finished in %s seconds", $executionTime));
        $this->logger->info("Process has been finished!");
    }

    /**
     * @throws FileNotFoundException
     * @throws UnsupportedFormatException
     */
    private function printData($data): void
    {
        $settings = $this->config->get(Constants::CONFIG_FILE_NAME);
        if($settings['DATA_EXPORT_TO_FILE']){
            $this->dataExportService->exportToFile($data);
        }else{
            $this->dataExportService->exportToConsole($data);
        }
    }
}
