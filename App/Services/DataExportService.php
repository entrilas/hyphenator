<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\Constants;

class DataExportService
{
    public function exportToFile($data): void
    {
        if(is_array($data)){
            file_put_contents(Constants::EXPORT_FILE_PATH, implode(PHP_EOL, $data));
        }else{
            file_put_contents(Constants::EXPORT_FILE_PATH, $data);
        }
    }

    public function exportToConsole($data): void
    {
        print_r($data);
    }
}