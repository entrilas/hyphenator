<?php

namespace App\Controllers\API;

use App\Models\Pattern;

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

    public function submit(array $params = [])
    {
        $this->validateNullableData($params);
        return $this->pattern->submitPattern($params);
    }

    public function delete(array $params = []): bool|string
    {
        $this->validateDelete($params);
        return $this->pattern->deletePattern($params);
    }

    public function update(array $params = []): bool|string
    {
        $pattern = $this->show($params[0]);
        $this->validateNullableData($params);
        return $this->pattern->updatePattern($params);
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
