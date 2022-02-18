<?php

namespace App\Controllers\API;

use App\Algorithm\HyphenationTrie;
use App\Core\Exceptions\InvalidArgumentException;
use App\Models\Word;

class WordController
{
    public function __construct(
        private Word $word,
        private HyphenationTrie $hyphenationTrie
    ) {
    }

    public function showAll(): bool|string
    {
        return $this->word->getWords();
    }

    public function show(array $params = []): bool|string
    {
        $response = $this->word->getWord($params[0]);
        $this->validateShow($response);
        return $response;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function submit(array $params = [])
    {
        $this->validateSubmit($params);
        $hyphenatedWord = $this->hyphenationTrie->hyphenate($params['word']);
        $params['hyphenated_word'] = $hyphenatedWord;
        return $this->word->submitWord($params);
    }

    public function delete(array $params = []): bool|string
    {
        $this->validateDelete($params[0]);
        return $this->word->deleteWord($params[0]);
    }

    public function update(array $params = []): bool|string
    {
        $word = $this->show($params[0]);
        $this->validateUpdate($params);
        return $this->word->updateWord($params);
    }

    private function validateSubmit(array $params)
    {
        if(is_null($params['word']) || !ctype_alpha($params['word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    private function validateDelete(array $params)
    {
        if(!is_numeric($params[0])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }elseif(!$this->show($params)){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
    }

    private function validateShow(string $response)
    {
        $response == 'false' ? $response = false : null;
        if(!$response){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
    }

    private function validateUpdate(array $params)
    {
         if(is_null($params['word']) &&
            is_null($params['hyphenated_word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }
}