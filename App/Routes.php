<?php

namespace App;

use App\Core\DI\Container;
use App\Requests\Pattern\DeletePatternRequest;
use App\Requests\Pattern\PatternRequest;
use App\Requests\Pattern\StorePatternRequest;
use App\Requests\Pattern\UpdatePatternRequest;
use App\Requests\Word\DeleteWordRequest;
use App\Requests\Word\StoreWordRequest;
use App\Requests\Word\UpdateWordRequest;
use App\Requests\Word\WordRequest;

$container = new Container;
$router = $container->get('App\\Core\\Router');
$response = $container->get('App\\Core\\Response');

// Words API Endpoints

$router->post(
    '/api/words',
    function (array $params = []) use ($container, $response) {
        $wordController = $container->get('App\\Controllers\\Api\\WordController');
        $storeWordRequest = new StoreWordRequest($params, $response);
        $wordController->submit($storeWordRequest);
    }
);

$router->delete(
    '/api/words/:id',
    function (array $params = []) use ($container, $response) {
        $wordController = $container->get('App\\Controllers\\Api\\WordController');
        $deleteWordRequest = new DeleteWordRequest($params, $response);
        $wordController->delete($deleteWordRequest);
    }
);

$router->update(
    '/api/words/:id',
    function (array $params = []) use ($container, $response) {
        $wordController = $container->get('App\\Controllers\\Api\\WordController');
        $updateWordRequest = new UpdateWordRequest($params, $response);
        $wordController->update($updateWordRequest);
    }
);

$router->get(
    '/api/words',
    function (array $params = []) use ($container) {
        $wordRequest = new WordRequest($params);
        $wordController = $container->get('App\\Controllers\\Api\\WordController');
        $wordController->showAll($wordRequest);
    }
);

$router->get(
    '/api/words/:id',
    function (array $params = []) use ($container, $response) {
        $wordController = $container->get('App\\Controllers\\Api\\WordController');
        $wordRequest = new WordRequest($params, $response);
        $wordController->show($wordRequest);
    }
);

// Patterns API Endpoints

$router->post(
    '/api/patterns',
    function (array $params = []) use ($container, $response) {
        $patternController = $container->get('App\\Controllers\\Api\\PatternController');
        $storePatternRequest = new StorePatternRequest($params, $response);
        $patternController->submit($storePatternRequest);
    }
);

$router->get(
    '/api/patterns',
    function (array $params = []) use ($container) {
        $patternRequest = new PatternRequest($params);
        $patternController = $container->get('App\\Controllers\\Api\\PatternController');
        $patternController->showAll($patternRequest);
    }
);

$router->get(
    '/api/patterns/:id',
    function (array $params) use ($container, $response) {
        $patternController = $container->get('App\\Controllers\\Api\\PatternController');
        $patternRequest = new PatternRequest($params, $response);
        $patternController->show($patternRequest);
    }
);

$router->delete(
    '/api/patterns/:id',
    function (array $params = []) use ($container, $response) {
        $patternController = $container->get('App\\Controllers\\Api\\PatternController');
        $deletePatternRequest = new DeletePatternRequest($params, $response);
        $patternController->delete($deletePatternRequest);
    }
);

$router->update(
    '/api/patterns/:id',
    function (array $params = []) use ($container, $response) {
        $patternController = $container->get('App\\Controllers\\Api\\PatternController');
        $patternRequest = new UpdatePatternRequest($params, $response);
        $patternController->update($patternRequest);
    }
);

// Website Routes

$router->get(
    '/',
    function (array $params = []) use ($container) {
        $homeController = $container->get('App\\Controllers\\Web\\HomeController');
        echo $homeController->index();
    }
);

$router->get(
    '/strings',
    function (array $params = []) use ($container) {
        $wordController = $container->get('App\\Controllers\\Web\\WordController');
        echo $wordController->index();
    }
);

$router->get(
    '/strings/submit',
    function (array $params = []) use ($container) {
        $wordController = $container->get('App\\Controllers\\Web\\WordController');
        echo $wordController->submit();
    }
);

$router->get(
    '/strings/:id',
    function (array $params = []) use ($container, $response) {
        $wordController = $container->get('App\\Controllers\\Web\\WordController');
        $wordRequest = new WordRequest($params, $response);
        echo $wordController->update($wordRequest);
    }
);

$router->get(
    '/patterns',
    function (array $params = []) use ($container) {
        $patternController = $container->get('App\\Controllers\\Web\\PatternController');
        echo $patternController->index();
    }
);

$router->get(
    '/patterns/submit',
    function (array $params = []) use ($container) {
        $patternController = $container->get('App\\Controllers\\Web\\PatternController');
        echo $patternController->submit();
    }
);

$router->get(
    '/patterns/:id',
    function (array $params = []) use ($container, $response) {
        $patternController = $container->get('App\\Controllers\\Web\\PatternController');
        $patternRequest = new PatternRequest($params, $response);
        echo $patternController->update($patternRequest);
    }
);

$router->addNotFoundHandler(
    function () use ($container) {
        $homeController = $container->get('App\\Controllers\\Web\\HomeController');
        echo $homeController->notFound();
    }
);

$router->run();
