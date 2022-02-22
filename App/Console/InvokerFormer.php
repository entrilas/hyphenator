<?php

namespace App\Console;

use App\Algorithm\FileHyphenation;
use App\Algorithm\Hyphenation;
use App\Algorithm\SentenceHyphenation;
use App\Console\Commands\FileCommand;
use App\Console\Commands\MigrationCommand;
use App\Console\Commands\PatternCommand;
use App\Console\Commands\SentenceCommand;
use App\Console\Commands\WordCommand;
use App\Core\Database\Import;
use App\Core\Database\Migration;

class InvokerFormer
{
    public function __construct(
        private Import $importService,
        private Validator $validator,
        private Migration $migration,
        private Hyphenation $hyphenation,
        private FileHyphenation $fileHyphenation,
        private SentenceHyphenation $sentenceHyphenation
    ){
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