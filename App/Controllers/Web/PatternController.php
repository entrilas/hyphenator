<?php

/**
 * @file
 * Pattern Controller class used to define logic for website.
 */

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Requests\Pattern\PatternRequest;

class PatternController extends Controller
{
    public function index(): string
    {
        return $this->view('patterns/index');
    }

    public function submit(): string
    {
        return $this->view('patterns/patterns-submit');
    }

    public function update(PatternRequest $request): string
    {
        return $this->view('patterns/patterns-update', ['id' => $request->getId()]);
    }
}
