<?php

namespace App\Requests;

class DeletePatternRequest
{
    public function __construct(private array $params)
    {
        $this->validateDelete($params);
    }

    private function validateDelete(array $params)
    {
        if(!is_numeric($params[0][0])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    public function getId()
    {
        return $this->params[0][0];
    }
}