<?php

class FileReader
{
    private $start = 0;
    public function readFiles($filename)
    {
        $fileData = array();
        $file = new SplFileObject($filename);
        $file->seek($file->getSize());
        $end = ($file->key() + 1);

        for ($i = 0; $i <= $end; $i++) {
            $file->seek($this->start + $i);
            $value = $file->current();
            array_push($fileData, $value);
        }
        return $fileData;
    }

    public function readFilesTest($path)
    {
        $file = array();

        if (!is_array($path)) {
            $file = file($path, FILE_IGNORE_NEW_LINES);
        }

        return $file;
    }
}