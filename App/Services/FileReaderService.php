<?php

namespace App\Services;

class FileReaderService
{
    public function readFiles($path)
    {
        $file = array();
        if (!is_array($path)) {
            $file = file($path, FILE_IGNORE_NEW_LINES);
        }
        return $file;
    }
}