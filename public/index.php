<?php

spl_autoload_register(function($class){
     require_once((dirname(__FILE__, 2).'/'.str_replace('\\', '/',$class).'.php'));
});

$App = new \App\Application();


