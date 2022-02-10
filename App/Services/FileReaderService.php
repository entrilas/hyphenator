<?php

namespace App\Services;

use App\Models\Pattern;

class FileReaderService extends FileReader
{
    public function readFile($path): array
    {
        return parent::readFile($path);
    }
}
