<?php

namespace App\Services;

use App\Constants\Constants;

class FileExportService
{
    public function exportFile($data)
    {
        if(is_array($data)){
            file_put_contents(Constants::EXPORT_FILE_PATH, implode(PHP_EOL, $data));
        }else{
            file_put_contents(Constants::EXPORT_FILE_PATH, $data);
        }


    }
}