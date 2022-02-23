<?php

declare(strict_types=1);

namespace App\Requests;

use App\Core\Response;

class StoreWordRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
        $this->validateSubmit();
    }

    private function validateSubmit()
    {
        if(is_null($this->getWord()) ||
            !ctype_alpha($this->getWord())
        ) {
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getWord(): string|null
    {
        return $this->params['word'];
    }

    public function getParams(): array|null
    {
        return $this->params;
    }
}