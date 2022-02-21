<?php

declare(strict_types=1);

namespace App\Requests;

class StoreWordRequest
{
    public function __construct(private array $params)
    {
        $this->validateSubmit($params);
    }

    private function validateSubmit(array $params)
    {
        if(is_null($params['word']) || !ctype_alpha($params['word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    public function getParams(): array
    {
        return $this->params;
    }
}