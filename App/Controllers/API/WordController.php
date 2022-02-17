<?php

namespace App\Controllers\API;

use App\Models\Word;

class WordController
{
    public function __construct(
        private Word $word
    ) {

    }

    public function showAll(): bool|string
    {
        return $this->word->getWords();
    }

    public function show(array $params = []): bool|string
    {
        return $this->word->getWord($params[0]);
    }

    public function submit(array $params = [])
    {
        $this->validateSubmit($params);
        return $this->word->submitWord($params);
    }

    public function delete(array $params = [])
    {
        $this->validateDelete($params[0]);
        return $this->word->deleteWord($params[0]);
    }

    private function validateSubmit(array $params)
    {
        if(is_null($params['word']) || is_null($params['hyphenated_word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
        }
    }

    private function validateDelete(array $params)
    {
        if(!is_numeric($params[0])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
        }
    }
}