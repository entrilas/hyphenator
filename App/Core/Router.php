<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $handlers;
    private $notFoundHandler;
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';
    private const METHOD_PUT = 'PUT';

    public function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        $callback = null;
        foreach($this->handlers as $handler)
        {
            $pathParsed = preg_replace('/[0-9]+/', ':id', $requestPath);
            preg_match_all('!\d+!', $requestPath, $idParsed);
            if($handler['path'] === $pathParsed && $method === $handler['method'])
            {
                $callback = $handler['handler'];
            }
        }

        if(!$callback || sizeof($idParsed) > 1){
            header("HTTP/1.0 404 Not Found");
            if(!empty($this->notFoundHandler)){
                $callback = $this->notFoundHandler;
            }
        }
        call_user_func_array($callback, [
            array_merge($_GET, $_POST, $idParsed)
        ]);
    }

    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    public function update(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_PUT, $path, $handler);
    }

    public function delete(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_DELETE, $path, $handler);
    }

    public function addNotFoundHandler($handler): void
    {
        $this->notFoundHandler = $handler;
    }

    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = [
            'path' => $path,
            'method' => $method,
            'handler' => $handler
        ];
    }
}