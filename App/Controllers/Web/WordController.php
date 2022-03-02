<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Requests\Word\WordRequest;

class WordController extends Controller
{
    public function index()
    {
        return $this->view('words/index');
    }

    public function submit()
    {
        return $this->view('words/words-submit');
    }

    public function update(WordRequest $request)
    {
        return $this->view('words/words-update', ['id' => $request->getId()]);
    }
}