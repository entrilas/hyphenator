<?php

declare(strict_types=1);

/**
 * @file
 * Word Controller class used to define logic for website.
 */

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Requests\Word\WordRequest;

class WordController extends Controller
{
    public function index(): string
    {
        return $this->view('words/index');
    }

    public function submit(): string
    {
        return $this->view('words/words-submit');
    }

    public function update(WordRequest $request): string
    {
        return $this->view('words/words-update', ['id' => $request->getId()]);
    }
}
