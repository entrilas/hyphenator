<?php

declare(strict_types=1);

namespace App\Core;

class Timer
{
    private $beginTime;
    private $endTime;

    public function start() : void
    {
        $this->beginTime = microtime(true);
    }

    public function finish() : void
    {
        $this->endTime = microtime(true);
    }

    private function calculateTime() : float
    {
        return $this->endTime - $this->beginTime;
    }

    public function getTime(): string
    {
        return number_format($this->calculateTime(), 5);
    }
}