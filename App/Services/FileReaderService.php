<?php

namespace App\Services;

class FileReaderService
{
    public function readFile($path)
    {
        $file = array();
        if (!is_array($path)) {
            $file = file($path, FILE_IGNORE_NEW_LINES);
        }
        return $file;
    }
}