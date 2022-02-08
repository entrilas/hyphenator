<?php

namespace App\Models;

class Word
{
    private $wordName;

    public function __construct($name){
        $this->wordName = $name;
    }

    public function setWord($name){
        $this->wordName = $name;
    }

    public function getWord(){
        return $this->wordName;
    }
}