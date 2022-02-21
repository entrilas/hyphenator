<?php

namespace App;

use App\Algorithm\Hyphenation;
use App\Algorithm\HyphenationTrie;
use App\Controllers\API\PatternController;
use App\Controllers\API\WordController;
use App\Core\Cache\Cache;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\Database\Export;
use App\Core\Database\QueryBuilder;
use App\Core\DI\Container;
use App\Core\Log\Logger;
use App\Core\Router;
use App\Core\Settings;
use App\Models\Pattern;
use App\Models\ValidPattern;
use App\Models\Word;
use App\Services\PatternReaderService;

$container = new Container;
$router = $container->get('App\\Core\\Router');

// WORDS API ENDPOINTS

$router->post('/api/words', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    print_r($wordController->submit($params));
});

$router->delete('/api/words/:id', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    print_r($wordController->delete($params[0][0]));
});

$router->update('/api/words/:id', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    print_r($wordController->update($params));
});

$router->get('/api/words', function() use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    print_r($wordController->showAll());
});

$router->get('/api/words/:id', function(array $params = []) use ($container){
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    print_r($wordController->show($params[0][0]));
});

// PATTERNS API ENDPOINTS

$router->post('/api/patterns', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    print_r($patternController->submit($params));
});

$router->get('/api/patterns', function() use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    print_r($patternController->showAll());
});

$router->get('/api/patterns/:id', function(array $params) use ($container){
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    print_r($patternController->show($params[0]));
});

$router->delete('/api/patterns/:id', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    print_r($patternController->delete($params[0]));
});

$router->update('/api/patterns/:id', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    print_r($patternController->update($params));
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});

$router->run();