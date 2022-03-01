<?php

declare(strict_types=1);

namespace App\Requests\Word;

use App\Core\Response;

class DeleteWordRequest
{
    public function __construct(
        private array $params,
        private ?Response $response = NULL
    ) {
        $this->validateDelete();
    }

    private function validateDelete(): void
    {
        if(!is_numeric($this->getId())){
            $this->response->response('Unprocessable Entity', 'Data provided is incorrect.');
        }
    }

    public function getId(): int
    {
        return (int)$this->params[0][0];
    }
}
