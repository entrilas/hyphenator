<?php

namespace App;

use App\Algorithm\FileHyphenation;
use App\Algorithm\HyphenationTrie;
use App\Algorithm\SentenceHyphenation;
use App\Console\Console;
use App\Constants\Constants;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\Database\Migration;
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

        $fileReader = new FileReaderService();
        $patternReader = new PatternReaderService();
        $fileExportService = new FileExportService();

        $timer = new Timer();
        $logger = new Logger($config);
        $patternPath = (dirname(__FILE__, 2)
            . $settings['RESOURCES_PATH']
            . DIRECTORY_SEPARATOR
            . $settings['PATTERNS_NAME']);
        $migration = new Migration($logger);
        $patterns = $patternReader->readFile($patternPath);
        $hyphenationAlgorithm = new HyphenationTrie($patterns);
        $fileHyphenation = new FileHyphenation($hyphenationAlgorithm, $fileReader);
        $sentenceHyphenation = new SentenceHyphenation($hyphenationAlgorithm);

        $console = new Console(
            $hyphenationAlgorithm,
            $fileHyphenation,
            $sentenceHyphenation,
            $fileExportService,
            $logger,
            $timer,
            $migration
        );

        if(PHP_SAPI == "cli")
        {
            $console->runConsole();
        }
    }
}