<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Requests\Pattern\PatternRequest;

class PatternController extends Controller
{
    public function index()
    {
        return $this->view('patterns/index');
    }

    public function submit()
    {
        return $this->view('patterns/patterns-submit');
    }

    public function update(PatternRequest $request)
    {
        return $this->view('patterns/patterns-update', ['id' => $request->getId()]);
    }
}