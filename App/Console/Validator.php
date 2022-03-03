<?php

declare(strict_types=1);

namespace App\Console;

use App\Constants\Constants;
use App\Core\Exceptions\InvalidArgumentException;

class Validator
{
    public function __construct(
        private Input $inputReceiver
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateConsoleInput(): void
    {
        if(($this->inputReceiver->getData() === null ||
            $this->inputReceiver->getData() === '') &&
            ($this->inputReceiver->getFlag() === Constants::IMPORT_PATTERNS_COMMAND))
            throw new InvalidArgumentException('Data provided is null or empty');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateArgumentsCount(): void
    {
        if($this->inputReceiver->getArgumentsCount() > 3 ||
            $this->inputReceiver->getArgumentsCount() < 3)
            throw new InvalidArgumentException("Invalid arguments provided 
            (php index.php [flag] '[content]')");
    }
}
