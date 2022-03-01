<?php

namespace App\Controllers\Web;

use App\Core\Controller;

class PatternController extends Controller
{
    public function index()
    {
        $this->view('patterns/index');
    }
}