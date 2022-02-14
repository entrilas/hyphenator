<?php

namespace App;

use App\Algorithm\FileHyphenation;
use App\Algorithm\HyphenationTrie;
use App\Algorithm\SentenceHyphenation;
use App\Console\Console;
use App\Constants\Constants;
use App\Core\Config;
use App\Core\Database\Migration;
use App\Core\Database\PatternImport;
use App\Core\Database\QueryBuilder;
use App\Core\Log\Logger;
use App\Core\Parser\JSONParser;
use App\Core\Timer;
use App\Services\FileExportService;
use App\Services\FileReaderService;
use App\Services\PatternReaderService;
use Exception;

class Application
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $config = new Config(new JSONParser());
        $settings = $config->get(Constants::CONFIG_FILE_NAME);

        $fileReaderService = new FileReaderService();
        $patternReaderService = new PatternReaderService();
        $fileExportService = new FileExportService();
        $timer = new Timer();

        $logger = new Logger($config);
        $patternPath = (dirname(__FILE__, 2)
            . $settings['RESOURCES_PATH']
            . DIRECTORY_SEPARATOR
            . $settings['PATTERNS_NAME']);

        $migration = new Migration($logger);
        $queryBuilder = QueryBuilder::getInstanceOf();
        $patterns = $patternReaderService->readFile($patternPath);
        $hyphenationAlgorithm = new HyphenationTrie($patterns);
        $fileHyphenation = new FileHyphenation($hyphenationAlgorithm, $fileReaderService);
        $sentenceHyphenation = new SentenceHyphenation($hyphenationAlgorithm);
        $importPatternService = new PatternImport($queryBuilder, $patternReaderService);

        $console = new Console(
            $hyphenationAlgorithm,
            $fileHyphenation,
            $sentenceHyphenation,
            $fileExportService,
            $importPatternService,
            $logger,
            $timer,
            $migration,
            $patterns
        );

        if(PHP_SAPI == "cli")
        {
            $console->runConsole();
        }
    }
}