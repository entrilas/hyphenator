<?php

namespace App;

use App\Controllers\API\WordController;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\Database\QueryBuilder;
use App\Core\Router;
use App\Models\Pattern;
use App\Models\Word;

$config = new Config();
$database = new Database($config);
$queryBuilder = new QueryBuilder($database);
$pattern = new Pattern($queryBuilder);
$words = new Word($queryBuilder);

$router = new Router();
$router->get('/api/words', function() use ($words)
{
    print_r($words->getWords());
});
$router->post('/api/words', WordController::class . '::show');
$router->post('/api/patterns', WordController::class . '::show');
$router->get('/api/patterns', WordController::class . '::show');
$router->addNotFoundHandler(function(){
    echo "Not Found!";
});
$router->run();