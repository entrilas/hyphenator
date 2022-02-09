<?php

namespace App;

use App\Algorithm\HyphenationTree;
use App\Constants\Constants;
use App\Core\Config;
use App\Core\Log\Logger;
use App\Core\Timer;
use App\Services\FileReaderService;
use App\Services\PatternReaderService;

class Application
{
    public function __construct()
    {
        $config = new Config();
        $settings = $config->get(Constants::CONFIG_FILE_NAME);

        $fileReader = new FileReaderService();
        $patternReader = new PatternReaderService();

        $timer = new Timer();
        $logger = new Logger($config);
        $patternPath = (dirname(__FILE__, 2) .$settings['RESOURCES_PATH'].$settings['PATTERNS_NAME']);
        $patterns = $patternReader->readFile($patternPath);
        $algorithmTree = new HyphenationTree($patterns, $logger);

        $timer->start();
        $answer = $algorithmTree->hyphenate("priceless");
        print_r($answer.PHP_EOL);
        $timer->finish();
        $logger->info("Algorithm finished in {$timer->getTime()} seconds");
    }
}