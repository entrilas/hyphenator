<?php

namespace App\Controllers\API;

use App\Models\Pattern;
use App\Requests\DeletePatternRequest;
use App\Requests\PatternRequest;
use PDOException;

class PatternController
{
    public function __construct(
        private Pattern $pattern
    ) {

    }

    public function showAll(): bool|string
    {
        return $this->pattern->getPatterns();
    }

    public function show($id): bool|string
    {
        $response = $this->pattern->getPattern($id);
        $response == 'false' ? $response = false : null;
        if(!$response){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
        return $response;
    }

    public function showByName(string $name): bool|string
    {
        return $this->pattern->getPatternByName($name);
    }

    public function submit(PatternRequest $request)
    {
        $params = $request->getParams();
        var_dump($params);
        if($this->getIfExists($params['pattern']) != null){
            return $this->getIfExists($params['pattern']);
        }
        header('HTTP/1.1 201 OK', true, 201);
        return $this->pattern->submitPattern($params);
    }

    private function getIfExists(string $name)
    {
        $existingPattern = json_decode($this->showByName($name),true);
        if($existingPattern !== false){
            return json_encode($existingPattern, JSON_PRETTY_PRINT);
        }
        return null;
    }

    public function delete(DeletePatternRequest $request): bool|string
    {
        $id = $request->getId();
        if(!$this->show($id)) {
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
        return $this->pattern->deletePattern($id);
    }

    public function update(PatternRequest $request): bool|string|null
    {
        $params = $request->getParams();
        $pattern = $this->show($params[0]);
        try{
            return $this->pattern->updatePattern($params);
        }catch(PDOException $e)
        {
            header('HTTP/1.1 409 Conflict', true, 409);
            echo sprintf("Pattern [ %s ] already exists in database.",$params['pattern']);
        }
        return null;
    }
}
