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

// WORDS Api ENDPOINTS

$router->post('/api/words', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $storeWordRequest = new StoreWordRequest($params, $response);
    $wordController->submit($storeWordRequest);
});

$router->delete('/api/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $deleteWordRequest = new DeleteWordRequest($params, $response);
    $wordController->delete($deleteWordRequest);
});

$router->update('/api/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $updateWordRequest = new UpdateWordRequest($params, $response);
    $wordController->update($updateWordRequest);
});

$router->get('/api/words', function() use ($container) {
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $wordController->showAll();
});

$router->get('/api/words/:id', function(array $params = []) use ($container, $response){
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $wordRequest = new WordRequest($params, $response);
    $wordController->show($wordRequest);
});

// PATTERNS Api ENDPOINTS

$router->post('/api/patterns', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $storePatternRequest = new StorePatternRequest($params, $response);
    $patternController->submit($storePatternRequest);
});

$router->get('/api/patterns', function() use ($container) {
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $patternController->showAll();
});

$router->get('/api/patterns/:id', function(array $params) use ($container, $response){
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $patternRequest = new PatternRequest($params, $response);
    $patternController->show($patternRequest);
});

$router->delete('/api/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $deletePatternRequest = new DeletePatternRequest($params, $response);
    $patternController->delete($deletePatternRequest);
});

$router->update('/api/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $patternRequest = new UpdatePatternRequest($params, $response);
    $patternController->update($patternRequest);
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});

$router->run();
