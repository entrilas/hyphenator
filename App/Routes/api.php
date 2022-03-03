<?php

namespace App\Routes;

use App\Requests\Pattern\DeletePatternRequest;
use App\Requests\Pattern\PatternRequest;
use App\Requests\Pattern\StorePatternRequest;
use App\Requests\Pattern\UpdatePatternRequest;
use App\Requests\Word\DeleteWordRequest;
use App\Requests\Word\StoreWordRequest;
use App\Requests\Word\UpdateWordRequest;
use App\Requests\Word\WordRequest;

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

$router->get('/api/words', function(array $params = []) use ($container) {
    $wordRequest = new WordRequest($params);
    $wordController = $container->get('App\\Controllers\\Api\\WordController');
    $wordController->showAll($wordRequest);
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

$router->get('/api/patterns', function(array $params = []) use ($container) {
    $patternRequest = new PatternRequest($params);
    $patternController = $container->get('App\\Controllers\\Api\\PatternController');
    $patternController->showAll($patternRequest);
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