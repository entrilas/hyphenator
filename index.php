<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$fileReader = new App\Services\FileReaderService();
$content = $fileReader->readFiles(App\Constants\Constants::US_TEX_PATTERNS);
$algorithm = new App\Algorithm\Hyphenation($content);

App\Console\Logger::time("Hyphenation Algorithm");
$hyphenatedWord = $algorithm->hyphenate("mistranslate");
App\Console\Logger::info($hyphenatedWord);
App\Console\Logger::timeEnd("Hyphenation Algorithm");