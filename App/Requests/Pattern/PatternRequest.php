<?php

declare(strict_types=1);

namespace App\Requests\Pattern;

use App\Core\Response;

class PatternRequest
{
    public function __construct(
        private array $params,
        private ?Response $response = null
    ) {
    }

    public function getId(): int|null
    {
        return (int)$this->params[0][0];
    }

    public function getPage(): int
    {
        return (int)$this->params['page'];
    }
}
