<?php

namespace App\Controllers\Web;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->view('home');
    }

    public function notFound()
    {
        return $this->view('_404');
    }
}