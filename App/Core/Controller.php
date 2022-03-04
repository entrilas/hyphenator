<?php

namespace App\Core;

class Controller extends View
{
    public function view($view, $params = []): string
    {
        return $this->renderView($view, $params);
    }
}
