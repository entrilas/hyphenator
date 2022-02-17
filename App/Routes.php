<?php

namespace App;

use App\Controllers\API\PatternController;
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
$word = new Word($queryBuilder);

$router = new Router();
$router->get('/api/words', function() use ($word) {
    $wordController = new WordController($word);
    print_r($wordController->showAll());
});
$router->post('/api/words', WordController::class . '::show');
$router->get('/api/patterns', function() use ($pattern) {
    $patternController = new PatternController($pattern);
    print_r($patternController->showAll());
});
$router->addNotFoundHandler(function(){
    echo "Not Found!";
});
$router->run();