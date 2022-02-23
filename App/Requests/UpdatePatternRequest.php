<?php

namespace App\Requests;

use App\Core\Response;

class UpdatePatternRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
        $this->validateNullableData();
    }

    private function validateNullableData()
    {
        if(is_null($this->getPattern())){
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getPattern(): string|null
    {
        return $this->params['pattern'];
    }

    public function getParams(): array|null
    {
        return $this->params;
    }
}