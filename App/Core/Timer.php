<?php

namespace App\Core;

class Timer
{
    private $beginTime;
    private $endTime;

    public function start(){
        $this->beginTime = microtime(true);
    }

    public function finish(){
        $this->endTime = microtime(true);
    }

    private function calculateTime(){
        return $this->endTime - $this->beginTime;
    }
    public function getTime(){
        return number_format($this->calculateTime(), 5);
    }
}