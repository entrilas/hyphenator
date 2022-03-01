<?php

declare(strict_types=1);

namespace App\Requests\Word;

use App\Core\Response;

class WordRequest
{
    public function __construct(
        private array $params,
        private ?Response $response = NULL
    ) {
    }

    public function getId(): int
    {
        return (int)$this->params[0][0];
    }
}
