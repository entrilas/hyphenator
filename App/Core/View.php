<?php

declare(strict_types=1);

namespace App\Core;

class View
{

    public function renderView($view, array $params = []): array|bool|string
    {
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once dirname(__DIR__, 2) . "/public/views/layouts/main.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderViewOnly($view, array $params): bool|string
    {
        foreach ($params as $key => $value) {
            $key = $value;
        }
        ob_start();
        include_once dirname(__DIR__, 2) . "/public/views/$view.php";
        return ob_get_clean();
    }
}
