<?php

namespace App\Requests;

class UpdateWordRequest
{
    public function __construct(private array $params)
    {
        $this->validateUpdate($params);
    }

    private function validateUpdate(array $params = [])
    {
        if(is_null($params['word']) &&
            is_null($params['hyphenated_word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}