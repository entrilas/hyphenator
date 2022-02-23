<?php

declare(strict_types=1);

namespace App\Requests\Pattern;

use App\Core\Response;

class UpdatePatternRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
        $this->validateNullableData();
    }

    private function validateNullableData(): void
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
