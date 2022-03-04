<?php

/**
 * @file
 * Router file used to define instantiate WEB and API routes.
 */

namespace App;

use App\Core\DI\Container;

$container = new Container;
$router = $container->get('App\\Core\\Router');
$response = $container->get('App\\Core\\Response');

require_once __DIR__ . '/Routes/web.php';
require_once __DIR__ . '/Routes/api.php';

$router->run();
