<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__).'/'.str_replace('\\', '/',$class).'.php'));
});

$fileReader = new App\Services\FileReaderService();
$content = $fileReader->readFile(App\Constants\Constants::US_TEX_PATTERNS);

$algorithm = new App\Algorithm\Hyphenation($content);
$algorithmTree = new App\Algorithm\HyphenationTree($content);

$largeFileData = $fileReader->readFile('App/Resources/text.txt');

foreach($largeFileData as $word)
{
    $answer = $algorithmTree->hyphenate($word);
    print_r($answer.PHP_EOL);
}
\App\Core\Log\Logger::info("Test");
