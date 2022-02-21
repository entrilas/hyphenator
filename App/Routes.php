<?php

namespace App;

use App\Core\DI\Container;
use App\Requests\DeletePatternRequest;
use App\Requests\DeleteWordRequest;
use App\Requests\PatternRequest;
use App\Requests\StoreWordRequest;
use App\Requests\UpdateWordRequest;

$container = new Container;
$router = $container->get('App\\Core\\Router');
// WORDS API ENDPOINTS

$router->post('/api/words', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $storeWordRequest = new StoreWordRequest($params);
    print_r($wordController->submit($storeWordRequest));
});

$router->delete('/api/words/:id', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $deleteWordRequest = new DeleteWordRequest($params);
    print_r($wordController->delete($deleteWordRequest));
});

$router->update('/api/words/:id', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $updateWordRequest = new UpdateWordRequest($params);
    print_r($wordController->update($updateWordRequest));
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
    $patternRequest = new PatternRequest($params);
    print_r($patternController->submit($patternRequest));
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
    $deletePatternRequest = new DeletePatternRequest($params);
    print_r($patternController->delete($deletePatternRequest));
});

$router->update('/api/patterns/:id', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $patternRequest = new PatternRequest($params);
    print_r($patternController->update($patternRequest));
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});

$router->run();