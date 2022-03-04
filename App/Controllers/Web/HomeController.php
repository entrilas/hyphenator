<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): string
    {
        return $this->view('home');
    }

    public function notFound(): string
    {
        return $this->view('_404');
    }
}
