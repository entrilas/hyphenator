<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Settings;

class DataExportService
{
    public function __construct(
        private Settings $settings
    ) {
    }

    public function exportToFile($data): void
    {
        if (is_array($data)) {
            file_put_contents(
                $this->getPath(),
                implode(PHP_EOL, $data)
            );
        } else {
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
            $this->settings->getExportFileName();
    }
}
