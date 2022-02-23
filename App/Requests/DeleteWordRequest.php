<?php

namespace App\Requests;

use App\Core\Response;

class DeleteWordRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
        $this->validateDelete();
    }

    private function validateDelete()
    {
        if(!is_numeric($this->getId())){
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getId(): int|null
    {
        return (int)$this->params[0][0];
    }
}