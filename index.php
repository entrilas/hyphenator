<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$config = new \App\Core\Config();
$fileReader = new App\Services\FileReaderService();
$patternReader = new \App\Services\PatternReaderService();
$timer = new \App\Core\Timer();
$logger = new \App\Core\Log\Logger(new \App\Core\Config());
$data = $config->get(\App\Constants\Constants::LOGGER_FILE_NAME);
print_r($data);
$logger->info("Labas");
//$content = $patternReader->readFile(App\Constants\Constants::US_TEX_PATTERNS);
//
//$algorithmTree = new App\Algorithm\HyphenationTree($content);
//
//$largeFileData = $fileReader->readFile('App/Resources/text.txt');
//
//$timer->start();
//$answer = $algorithmTree->hyphenate("priceless");
//$timer->finish();
//$logger::info("Algorithm finished in {$timer->getTime()} seconds");
//print_r($answer.PHP_EOL);
//
