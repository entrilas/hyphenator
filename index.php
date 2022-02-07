<?php
spl_autoload_register(function($class){
    require_once($class.'.php');
});

$fileReader = new FileReader();
$logger = new Logger();
$content = $fileReader->readFilesTest('tex-hyphenation-patterns.txt');
$algorithm = new Hyphenator($content);
$algorithm->hyphenate("computer");
//$logger->logConsole($content);