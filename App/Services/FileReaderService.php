<?php

declare(strict_types=1);

namespace App\Services;

use SplFileObject;

class FileReaderService
{
//    public function readFile($path): array
//    {
//        foreach (new SplFileObject($path) as $line) {
//            $data[] = $line;
//        }
//        return $data;
//    }
    public function readFile($path): array
    {
        $file = array();
        if (!is_array($path)) {
            $file = file($path, FILE_IGNORE_NEW_LINES);
        }
        return $file;
    }
}