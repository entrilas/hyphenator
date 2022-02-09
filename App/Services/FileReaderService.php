<?php

namespace App\Services;

use App\Models\Pattern;

class FileReaderService extends FileReader
{
    public function readFile($path){
        return parent::readFile($path);
    }
}
