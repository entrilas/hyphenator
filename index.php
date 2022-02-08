<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$fileReader = new App\Services\FileReaderService();
$content = $fileReader->readFile(App\Constants\Constants::US_TEX_PATTERNS);
$algorithm = new App\Algorithm\Hyphenation($content);

//App\Console\Logger::time("File Reading with fread()");
//$largeFileData = $fileReader->readFile('App/Resources/text.txt');
//App\Console\Logger::timeEnd("File Reading with fread()");
//
//App\Console\Logger::time("File Reading with fgets()");
//$largeFileData = $fileReader->readLargeFile(App\Constants\Constants::LARGE_FILE);
//App\Console\Logger::timeEnd("File Reading with fgets()");


App\Console\Logger::time("Hyphenation Algorithm");
$hyphenatedWord = $algorithm->hyphenate("mistranslate");
App\Console\Logger::info($hyphenatedWord);
App\Console\Logger::timeEnd("Hyphenation Algorithm");