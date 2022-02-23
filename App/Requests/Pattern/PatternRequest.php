<?php

namespace App\Requests\Pattern;

use App\Core\Response;

class PatternRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
    }

    public function getPattern(): string|null
    {
        return $this->params['pattern'];
    }

    public function getId(): int|null
    {
        return (int)$this->params[0][0];
    }

    public function getParams(): array|null
    {
        return $this->params;
    }
}