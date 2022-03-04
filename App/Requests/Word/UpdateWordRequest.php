<?php

declare(strict_types=1);

namespace App\Requests\Word;

use App\Constants\ResponseCodes;
use App\Core\Response;
use App\Repository\WordRepository;

class UpdateWordRequest
{
    public function __construct(
        private array $params,
        private ?Response $response = null
    ) {
        $this->validateUpdate();
    }

    private function validateUpdate(): void
    {
        if (is_null($this->getWord())
            && is_null($this->getHyphenatedWord())
        ) {
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getWord(): string
    {
        return $this->params['word'];
    }

    public function getHyphenatedWord(): string
    {
        return $this->params['hyphenated_word'];
    }

    public function getId(): int
    {
        return (int)$this->params[0][0];
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
