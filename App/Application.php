<?php

declare(strict_types=1);

namespace App;

use App\Core\DI\Container;
use Exception;

class Application
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (PHP_SAPI === "cli") {
            $container = new Container;
            $console = $container->get('App\\Console\\Console');
            $console->runConsole();
        } else {
            include_once __DIR__.'/../App/Routes.php';
        }
    }
}
