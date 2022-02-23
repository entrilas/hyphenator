<?php

namespace App\Requests\Word;

use App\Core\Response;

class WordRequest
{
    public function __construct(
        private array $params,
        private Response $response
    ) {
    }

    public function getWord(): string|null
    {
        return $this->params['word'];
    }

    public function getHyphenatedWord(): string|null
    {
        return $this->params['hyphenated_word'];
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