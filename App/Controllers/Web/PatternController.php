<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Requests\Pattern\PatternRequest;

class PatternController extends Controller
{
    public function index()
    {
        $this->view('patterns/index');
    }

    public function submit()
    {
        $this->view('patterns/patterns-submit');
    }

    public function update(PatternRequest $request)
    {
        $this->view('patterns/patterns-update', ['id' => $request->getId()]);
    }
}