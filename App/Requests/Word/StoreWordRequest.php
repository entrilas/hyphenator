<?php

declare(strict_types=1);

namespace App\Requests\Word;

use App\Core\Response;

class StoreWordRequest
{
    public function __construct(
        private array $params,
        private ?Response $response = null
    ) {
        $this->validateSubmit();
    }

    private function validateSubmit(): void
    {
        if (is_null($this->getWord())
            || !ctype_alpha($this->getWord())
        ) {
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getWord(): string
    {
        return $this->params['word'];
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
