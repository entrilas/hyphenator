<?php

namespace App\Core;

class Controller
{
    public function view(string $view, array $data = [])
    {
        require_once sprintf(__DIR__ . '/../../public/views/%s.php', $view);
    }
}