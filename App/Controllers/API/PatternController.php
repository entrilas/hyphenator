<?php

namespace App\Controllers\API;

use App\Models\Pattern;
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

    public function show(array $params = []): bool|string
    {
        $response = $this->pattern->getPattern($params[0]);
        $this->validateShow($response);
        return $response;
    }

    public function showByName(string $name): bool|string
    {
        return $this->pattern->getPatternByName($name);
    }

    public function submit(array $params = [])
    {
        $this->validateNullableData($params);
        if($this->getIfExists($params['pattern']) != null)
            return $this->getIfExists($params['pattern']);
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

    public function delete($id): bool|string
    {
        $this->validateDelete($id);
        return $this->pattern->deletePattern($id);
    }

    public function update(array $params = []): bool|string|null
    {
        $pattern = $this->show($params[0]);
        $this->validateNullableData($params);
        try{
            return $this->pattern->updatePattern($params);
        }catch(PDOException $e)
        {
            header('HTTP/1.1 409 Conflict', true, 409);
            echo sprintf("Pattern [ %s ] already exists in database.",$params['pattern']);
        }
        return null;
    }

    private function validateShow(string $response)
    {
        $response == 'false' ? $response = false : null;
        if(!$response){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
    }

    private function validateDelete(array $params)
    {
        if(!is_numeric($params[0])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }elseif(!$this->show($params)){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
    }

    private function validateNullableData(array $pattern)
    {
        if(is_null($pattern['pattern'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }
}
