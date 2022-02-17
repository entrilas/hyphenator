<?php

require_once "autoloader.php";

use App\Application;

if(PHP_SAPI === "cli")
{
    $App = new Application();
    $App->console->runConsole();
}else{
    require_once __DIR__.'/../App/Routes.php';
}

