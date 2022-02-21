<?php

namespace App\Requests;

class PatternRequest
{
    public function __construct(private array $params)
    {
        $this->validateNullableData($params);
    }

    private function validateNullableData(array $params)
    {
        if(is_null($params['pattern'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}