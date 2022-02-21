<?php

namespace App\Controllers\API;

use App\Algorithm\Hyphenation;
use App\Models\Word;
use PDOException;

class WordController
{
    public function __construct(
        private Word $word,
        private Hyphenation $hyphenation
    ) {
    }

    public function showAll(): bool|string
    {
        return $this->word->getWords();
    }

    public function show($id): bool|string
    {
        $response = $this->word->getWord($id);
        $this->validateShow($response);
        return $response;
    }

    public function showByName(string $name): bool|string
    {
        return $this->word->getWordByName($name);
    }

    public function submit(array $params = [])
    {
        $this->validateSubmit($params);
        if($this->getIfExists($params['word']) != null){
            return $this->getIfExists($params['word']);
        }
        $hyphenatedWord = $this->hyphenation->hyphenate($params['word']);
        $params['hyphenated_word'] = $hyphenatedWord;
        header('HTTP/1.1 201 OK', true, 201);
        return $this->word->submitWord($params);
    }

    private function getIfExists(string $name): string|null
    {
        $existingWord = json_decode($this->showByName($name),true);
        if($existingWord !== false){
            return json_encode($existingWord, JSON_PRETTY_PRINT);
        }
        return null;
    }

    public function delete($id): bool|string
    {
        $this->validateDelete($id);
        return $this->word->deleteWord($id);
    }

    public function update(array $params = []): bool|string|null
    {
        $word = $this->show($params[0][0]);
        $this->validateUpdate($params);
        try{
            return $this->word->updateWord($params);
        }catch(PDOException $e)
        {
            header('HTTP/1.1 409 Conflict', true, 409);
            echo sprintf("Word [ %s ] already exists in database.",$params['word']);
        }
        return null;
    }

    private function validateSubmit(array $params)
    {
        if(is_null($params['word']) || !ctype_alpha($params['word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }

    private function validateDelete($id)
    {
        if(!is_numeric($id)){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }elseif(!$this->show($id)){
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

    private function validateUpdate(array $params = [])
    {
         if(is_null($params['word']) &&
            is_null($params['hyphenated_word'])){
            header('HTTP/1.1 422 Unprocessable Entity', true, 422);
            exit();
        }
    }
}