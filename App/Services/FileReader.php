<?php

namespace App\Services;

abstract class FileReader
{
    public function readFile($path){
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