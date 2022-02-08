<?php

namespace App\Services;

use App\Models\Pattern;

class FileReaderService
{
//    public function readSmallFile($path)
//    {
//        $file = array();
//        if (!is_array($path)) {
//            $file = file($path, FILE_IGNORE_NEW_LINES);
//        }
//        return $file;
//    }

    public function readFile($path)
    {
        $fp = @fopen($path, 'r');

        if ($fp) {
            $array = explode("\n", fread($fp, filesize($path)));
        }
        return $array;
    }

    public function readLargeFile($path)
    {
        $handle = fopen($path, "r");
        if ($handle) {
            while (!feof($handle)) {
                $patterns[] = fgets($handle, 4096);
            }
            fclose($handle);
        }
        return $patterns;
    }
}