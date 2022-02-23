<?php

namespace App;

use App\Core\DI\Container;
use App\Models\Word;
use App\Requests\DeletePatternRequest;
use App\Requests\DeleteWordRequest;
use App\Requests\PatternRequest;
use App\Requests\StorePatternRequest;
use App\Requests\StoreWordRequest;
use App\Requests\UpdateWordRequest;
use App\Requests\WordRequest;

$container = new Container;
$router = $container->get('App\\Core\\Router');
$response = $container->get('App\\Core\\Response');

// WORDS API ENDPOINTS

$router->post('/api/words', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $storeWordRequest = new StoreWordRequest($params, $response);
    $wordController->submit($storeWordRequest);
});

$router->delete('/api/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $deleteWordRequest = new DeleteWordRequest($params, $response);
    $wordController->delete($deleteWordRequest);
});

$router->update('/api/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $updateWordRequest = new UpdateWordRequest($params, $response);
    $wordController->update($updateWordRequest);
});

$router->get('/api/words', function() use ($container) {
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $wordController->showAll();
});

$router->get('/api/words/:id', function(array $params = []) use ($container, $response){
    $wordController = $container->get('App\\Controllers\\API\\WordController');
    $wordRequest = new WordRequest($params, $response);
    $wordController->show($wordRequest);
});

// PATTERNS API ENDPOINTS

$router->post('/api/patterns', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $storePatternRequest = new StorePatternRequest($params, $response);
    $patternController->submit($storePatternRequest);
});

$router->get('/api/patterns', function() use ($container) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $patternController->showAll();
});

$router->get('/api/patterns/:id', function(array $params) use ($container, $response){
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $patternRequest = new PatternRequest($params, $response);
    $patternController->show($patternRequest);
});

$router->delete('/api/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $deletePatternRequest = new DeletePatternRequest($params, $response);
    $patternController->delete($deletePatternRequest);
});

$router->update('/api/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\API\\PatternController');
    $patternRequest = new PatternRequest($params, $response);
    $patternController->update($patternRequest);
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});

$router->run();