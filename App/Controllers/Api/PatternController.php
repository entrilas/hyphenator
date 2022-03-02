<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Constants\ResponseCodes;
use App\Core\Response;
use App\Models\Pattern;
use App\Repository\PatternRepository;
use App\Requests\Pattern\DeletePatternRequest;
use App\Requests\Pattern\PatternRequest;
use App\Requests\Pattern\StorePatternRequest;
use App\Requests\Pattern\UpdatePatternRequest;
use PDOException;

class PatternController
{
    public function __construct(
        private PatternRepository $patternRepository,
        private Response $response
    ) {
    }

    public function showAll(PatternRequest $request): bool|string
    {
        if($request->getPage() === 0){
            $patterns = $this->patternRepository->getPatterns();
        }else{
            $patterns = $this->patternRepository->getPatternsByPage($request->getPage());
        }

        if($patterns === false){
            return $this->response->response(
                ResponseCodes::NOT_FOUND_ERROR_NAME,
                "Words was not found",
            );
        }
        return $this->response->response(
            ResponseCodes::OK_ERROR_NAME,
            "Patterns provided below have been found",
            $patterns
        );
    }

    public function show(PatternRequest $request): bool|string
    {
        $pattern = $this->patternRepository->getPattern($request->getId());
        if(!$pattern){
            $this->response->response(
                    ResponseCodes::NOT_FOUND_ERROR_NAME,
                    sprintf("Pattern with id [%s] was not found", $request->getId())
            );
        }
        return $this->response->response(
            ResponseCodes::OK_ERROR_NAME,
            sprintf("Pattern with id [%s] was found", $request->getId()),
            ['pattern' => $pattern->getPattern(),
             'id' => $pattern->getId()]
        );
    }

    /**
     * @throws Exception
     */
    public function submit(StorePatternRequest $request)
    {
        $pattern = $this->patternRepository->getPatternByName($request->getPattern());
        if($pattern !== false){
            return $this->response->response(
                ResponseCodes::CREATED_ERROR_NAME,
                "Pattern is already created and found in database.",
                ['id' => $pattern->getId(),
                 'pattern' => $pattern->getPattern()
                ]
            );
        }
        $this->patternRepository->submitPattern($request->getPattern());
        return $this->response->response(
            ResponseCodes::CREATED_ERROR_NAME,
            "Pattern has been created."
        );
    }

    public function delete(DeletePatternRequest $request): bool|string
    {
        $id = $request->getId();
        if(!$this->patternRepository->getPattern($id)) {
            return $this->response->response(
                ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Pattern with id [%s] has not been found.",
                    $id)
            );
        }
        $this->patternRepository->deletePattern($id);
        return $this->response->response(
            ResponseCodes::OK_ERROR_NAME,
            sprintf("Pattern with id [%s] has been deleted.",
                $id)
        );
    }

    public function update(UpdatePatternRequest $request): ?string
    {
        if(!$this->checkIfPatternExists($request)){
            return $this->response->response(
                ResponseCodes::NOT_FOUND_ERROR_NAME,
                sprintf("Pattern with id [%s] has not been found.",
                    $request->getId())
            );
        }
        try{
            $this->patternRepository->updatePattern(
                $request->getId(),
                $request->getPattern()
            );
            return $this->response->response(ResponseCodes::OK_ERROR_NAME,
                sprintf("Pattern with id [%s] has been updated.", $request->getId())
            );
        }catch(PDOException $e)
        {
            return $this->response->response(ResponseCodes::CONFLICT_ERROR_NAME,
                sprintf("Pattern with id [%s] is already created.", $request->getId())
            );
        }
    }

    private function checkIfPatternExists(UpdatePatternRequest $request): Pattern|bool
    {
        return $this->patternRepository->getPattern($request->getId());
    }
}
