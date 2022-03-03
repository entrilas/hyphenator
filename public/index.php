<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));

require_once "../vendor/autoload.php";

use App\Application;

$App = new Application();
