<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Settings;

class DataExportService
{
    private array $applicationSettings;

    public function __construct(
        private Settings $settings
    ) {
        $this->applicationSettings = $this->settings->getConfig();
    }

    public function exportToFile($data): void
    {
        if(is_array($data)){
            file_put_contents(
                $this->getPath(),
                implode(PHP_EOL, $data)
            );
        }else{
            file_put_contents(
                $this->getPath(),
                $data
            );
        }
    }

    public function exportToConsole($data): void
    {
        print_r($data);
    }

    private function getPath(): string
    {
        return dirname(__FILE__, 3) .
            $this->applicationSettings['EXPORT_DATA_PATH'];
    }
}
