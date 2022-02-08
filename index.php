<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$fileReader = new App\Services\FileReaderService();
$content = $fileReader->readFile(App\Constants\Constants::US_TEX_PATTERNS);

$algorithm = new App\Algorithm\Hyphenation($content);
$algorithmTree = new App\Algorithm\HyphenationTree($content);

$largeFileData = $fileReader->readFile('App/Resources/text.txt');

App\Console\Logger::time("Hyphenation (Simple)");
foreach($largeFileData as $word)
{
    $answer = $algorithm->hyphenate($word);
//    print_r($answer.PHP_EOL);
}
App\Console\Logger::timeEnd("Hyphenation (Simple)");

App\Console\Logger::time("Hyphenation (Tree)");
foreach($largeFileData as $word)
{
    $answer = $algorithmTree->hyphenate($word);
//    print_r($answer.PHP_EOL);
}
App\Console\Logger::timeEnd("Hyphenation (Tree)");


