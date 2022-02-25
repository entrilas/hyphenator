<?php

declare(strict_types=1);

namespace App\Controllers\API;

use App\Algorithm\Hyphenation;
use App\Constants\ResponseCodes;
use App\Core\Response;
use App\Models\Word;
use App\Repository\WordRepository;
use App\Requests\Word\DeleteWordRequest;
use App\Requests\Word\StoreWordRequest;
use App\Requests\Word\UpdateWordRequest;
use App\Requests\Word\WordRequest;
use Exception;
use PDOException;

class WordController
{
    public function __construct(
        private WordRepository $wordRepository,
        private Hyphenation $hyphenation,
        private Response $response
    ) {
    }

    public function showAll(): bool|string
    {
        $words = $this->wordRepository->getWords();
        if($words === false){
            return $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                "Words was not found",
            );
        }

        return $this->response->response(ResponseCodes::OK_ERROR_NAME,
            "Words provided below have been found",
            $words
        );
    }

    public function show(WordRequest $request): bool|string
    {
        $word = $this->wordRepository->getWord($request->getId());
        if(!$word){
            $this->response->response(
                ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Word with id [%s] was not found", $request->getId())
            );
        }
        return $this->response->response(
            ResponseCodes::OK_ERROR_NAME,
            sprintf("Word with id [%s] was found", $request->getId()),
            ['word' => $word->getWord(),
             'id' => $word->getId(),
             'hyphenated_word' => $word->getHyphenatedWord()
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function submit(StoreWordRequest $request): string
    {
        $word = $this->wordRepository->getWordByName($request->getWord());
        if($word !== false){
            return $this->response->response(ResponseCodes::CREATED_ERROR_NAME,
                "Word is already created and found in database.",
                ['word' => $word->getWord(),
                 'id' => $word->getId(),
                  'hyphenated_word' => $word->getHyphenatedWord()
                ]
            );
        }
        $this->hyphenation->hyphenate($request->getWord());
        return $this->response->response(ResponseCodes::CREATED_ERROR_NAME,
            "Word has been created."
        );
    }

    public function delete(DeleteWordRequest $request): bool|string
    {
        $id = $request->getId();
        if(!$this->wordRepository->getWord($id)) {
            return $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Word with id [%s] has not been found.", $id));
        }
        $this->wordRepository->deleteWord($id);
        return $this->response->response(ResponseCodes::OK_ERROR_NAME,
            sprintf("Word with id [%s] has been deleted.", $id));
    }

    public function update(UpdateWordRequest $request): ?string
    {
        if($this->checkIfWordExists($request) === false){
            return $this->response->response(ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Word with id [%s] has not been found", $request->getId())
            );
        }
        try{
            $wordModel = $this->wordRepository->updateWord(
                $request->getId(),
                $request->getWord(),
                $request->getHyphenatedWord()
            );
            var_dump($wordModel);
            return $this->response->response(ResponseCodes::OK_ERROR_NAME,
                sprintf("Word with id [%s] has been updated.", $request->getId())
            );
        }catch(PDOException $e)
        {
            return $this->response->response(ResponseCodes::CONFLICT_ERROR_NAME,
                sprintf("Word with id [%s] is already created.", $request->getId())
            );
        }
    }

    private function checkIfWordExists(UpdateWordRequest $request): Word|bool
    {
        return $this->wordRepository->getWord($request->getId());
    }
}
