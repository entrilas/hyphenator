<?php

namespace App\Services;

use App\Constants\Constants;

class FileExportService
{
    public function exportFile($data)
    {
        file_put_contents(Constants::EXPORT_FILE_PATH, $data);
    }
}