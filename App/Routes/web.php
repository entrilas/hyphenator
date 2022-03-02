<?php

namespace App\Routes;

use App\Requests\Pattern\PatternRequest;
use App\Requests\Word\WordRequest;

$router->get('/', function(array $params = []) use ($container) {
    $homeController = $container->get('App\\Controllers\\Web\\HomeController');
    echo $homeController->index();
});

$router->get('/words', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    echo $wordController->index();
});

$router->get('/words/submit', function(array $params = []) use ($container) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    echo $wordController->submit();
});

$router->get('/words/:id', function(array $params = []) use ($container, $response) {
    $wordController = $container->get('App\\Controllers\\Web\\WordController');
    $wordRequest = new WordRequest($params, $response);
    echo $wordController->update($wordRequest);
});

$router->get('/patterns', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    echo $patternController->index();
});

$router->get('/patterns/submit', function(array $params = []) use ($container) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    echo $patternController->submit();
});

$router->get('/patterns/:id', function(array $params = []) use ($container, $response) {
    $patternController = $container->get('App\\Controllers\\Web\\PatternController');
    $patternRequest = new PatternRequest($params, $response);
    echo $patternController->update($patternRequest);
});

$router->addNotFoundHandler(function(){
    echo "Not Found!";
});