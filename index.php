<?php
spl_autoload_register(function($class){
    require_once($class.'.php');
});

$fileReader = new FileReader();
$logger = new Logger();
$content = $fileReader->readFilesTest('tex-hyphenation-patterns.txt');
$algorithm = new Hyphenator($content);

Logger::time("Hyphenation Algorithm");
$hyphenatedWord = $algorithm->hyphenate("mistranslate");
Logger::info($hyphenatedWord);
Logger::timeEnd("Hyphenation Algorithm");