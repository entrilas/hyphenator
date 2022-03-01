<?php

namespace App\Routes;

use App\Requests\Pattern\PatternRequest;
use App\Requests\Word\WordRequest;

$router->get('/', function(array $params = []) use ($container) {
    $homeController = $container->get('App\\Controllers\\Web\\HomeController');
    $homeController->index();
});

$router->get('/words', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    $wordController->index();
});

$router->get('/words/submit', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    $wordController->submit();
});

$router->get('/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    $wordRequest = new WordRequest($params, $response);
    $wordController->update($wordRequest);
});

$router->get('/patterns', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    $patternController->index();
});

$router->get('/patterns/submit', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    $patternController->submit();
});

$router->get('/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    $patternRequest = new PatternRequest($params, $response);
    $patternController->update($patternRequest);
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});