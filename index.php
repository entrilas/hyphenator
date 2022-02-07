<?php
spl_autoload_register(function($class){
    require_once($class.'.php');
});

$fileReader = new FileReader();
$logger = new Logger();
$content = $fileReader->readFilesTest('tex-hyphenation-patterns.txt');
$algorithm = new Hyphenator($content);

$start = microtime(true);
print_r($algorithm->hyphenate("computer"));
$time_elapsed_secs = microtime(true) - $start;
//$logger->logConsole($content);