<?php

declare(strict_types=1);

namespace App\Controllers\API;

use App\Algorithm\Hyphenation;
use App\Constants\ResponseCodes;
use App\Core\Response;
use App\Models\Word;
use App\Requests\Word\DeleteWordRequest;
use App\Requests\Word\StoreWordRequest;
use App\Requests\Word\UpdateWordRequest;
use App\Requests\Word\WordRequest;
use Exception;
use PDOException;

class WordController
{
    public function __construct(
        private Word $word,
        private Hyphenation $hyphenation,
        private Response $response
    ) {
    }

    public function showAll(): bool|string
    {
        $words = $this->word->getWords();
        return $this->response->response(ResponseCodes::OK_ERROR_NAME,
            "Words provided below have been found",
            $words
        );
    }

    public function show(WordRequest $request): bool|string
    {
        $response = $this->word->getWord($request->getId());
        if(!$response){
            $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                    sprintf("Word with id [%s] was not found",
                    $request->getId())
            );
        }
        return $this->response->response(ResponseCodes::OK_ERROR_NAME,
            sprintf("Word with id [%s] was found", $request->getId()),
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function submit(StoreWordRequest $request)
    {
        if($this->word->getWordByName($request->getWord()) !== false){
            return $this->response->response(ResponseCodes::CONFLICT_ERROR_NAME,
                "Word is already created and found in database.",
                $this->word->getWordByName($request->getWord()));
        }
        $this->hyphenation->hyphenate($request->getWord());
        return $this->response->response(ResponseCodes::CREATED_ERROR_NAME,
            "Word has been created.",
            $this->word->getWordByName($request->getWord()));
    }

    public function delete(DeleteWordRequest $request): bool|string
    {
        $id = $request->getId();
        if(!$this->word->getWord($id)) {
            return $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Word with id [%s] has not been found.", $id));
        }
        $this->word->deleteWord($id);
        return $this->response->response(ResponseCodes::OK_ERROR_NAME,
            sprintf("Word with id [%s] has been deleted.", $id));
    }

    public function update(UpdateWordRequest $request): bool|string|null
    {
        $this->checkIfExists($request);
        try{
            $this->word->updateWord($request->getParams());
            return $this->response->response(ResponseCodes::OK_ERROR_NAME,
                sprintf("Word with id [%s] has been updated.", $request->getId()));
        }catch(PDOException $e)
        {
            return $this->response->response(ResponseCodes::CONFLICT_ERROR_NAME,
                sprintf("Word with id [%s] is already created.", $request->getId()));
        }
        return null;
    }

    private function checkIfExists(UpdateWordRequest $request)
    {
        if(!$this->word->getWord($request->getId())){
            return $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Word with id [%s] has not been found!", $request->getId()));
        }
    }
}
