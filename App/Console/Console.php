<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\PatternCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Core\Exceptions\InvalidArgumentException;
use App\Core\Log\Logger;
use App\Core\Settings;
use App\Core\Timer;
use App\Services\DataExportService;
use Exception;

class Console
{
    public function __construct(
        private Settings $settings,
        private DataExportService $dataExportService,
        private Logger $logger,
        private Timer $timer,
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
        $this->validator->validateArgumentsCount();
        $this->validator->validateConsoleInput();
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

    private function printData($data): void
    {
        if($this->settings->getExportToFileStatus()){
            $this->dataExportService->exportToFile($data);
        }else{
            $this->dataExportService->exportToConsole($data);
        }
    }
}
