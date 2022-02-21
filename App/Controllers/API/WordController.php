<?php

namespace App\Controllers\API;

use App\Algorithm\Hyphenation;
use App\Models\Word;
use App\Requests\DeleteWordRequest;
use App\Requests\ShowWordRequest;
use App\Requests\StoreWordRequest;
use App\Requests\UpdateWordRequest;
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
        $response == 'false' ? $response = false : null;
        if(!$response){
            header('HTTP/1.1 404 Not Found', true, 404);
            exit();
        }
        return $response;
    }

    public function showByName(string $name): bool|string
    {
        return $this->word->getWordByName($name);
    }

    public function submit(StoreWordRequest $request)
    {
        $params = $request->getParams();
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

    public function delete(DeleteWordRequest $request): bool|string
    {
        $id = $request->getId();
        return $this->word->deleteWord($id);
    }

    public function update(UpdateWordRequest $request): bool|string|null
    {
        $params = $request->getParams();
        $word = $this->show($params[0][0]);
        try{
            return $this->word->updateWord($params);
        }catch(PDOException $e)
        {
            header('HTTP/1.1 409 Conflict', true, 409);
            echo sprintf("Word [ %s ] already exists in database.",$params['word']);
        }
        return null;
    }
}
