<?php

namespace App\Models;

class Pattern
{
    private $patternName;

    public function __construct($name){
        $this->patternName = $name;
    }

    public function setPattern($name){
        $this->patternName = $name;
    }

    public function getPattern(){
        return $this->patternName;
    }
}