<?php

namespace App;

use App\Core\DI\Container;
use App\Requests\Pattern\DeletePatternRequest;
use App\Requests\Pattern\UpdatePatternRequest;
use App\Requests\Word\DeleteWordRequest;
use App\Requests\Pattern\PatternRequest;
use App\Requests\Pattern\StorePatternRequest;
use App\Requests\Word\StoreWordRequest;
use App\Requests\Word\UpdateWordRequest;
use App\Requests\Word\WordRequest;

$container = new Container;
$router = $container->get('App\\Core\\Router');
$response = $container->get('App\\Core\\Response');

require_once __DIR__ . '/Routes/web.php';
require_once __DIR__ . '/Routes/api.php';

$router->run();
