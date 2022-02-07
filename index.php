<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$fileReader = new \App\Services\FileReaderService();
$logger = new \App\Console\Logger();
$content = $fileReader->readFilesTest(\App\Constants\Constants::US_TEX_PATTERNS);
$algorithm = new App\Algorithm\Hyphenation($content);

Logger::time("Hyphenation Algorithm");
$hyphenatedWord = $algorithm->hyphenate("mistranslate");
Logger::info($hyphenatedWord);
Logger::timeEnd("Hyphenation Algorithm");