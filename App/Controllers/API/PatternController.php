<?php

namespace App\Controllers\API;

use App\Core\Response;
use App\Models\Pattern;
use App\Requests\DeletePatternRequest;
use App\Requests\PatternRequest;
use App\Requests\StorePatternRequest;
use App\Requests\UpdatePatternRequest;
use PDOException;

class PatternController
{
    public function __construct(
        private Pattern $pattern,
        private Response $response
    ) {
    }

    public function showAll(): bool|string
    {
        $patterns = $this->pattern->getPatterns();
        return $this->response->response('Ok',
            "Patterns provided below have been found",
            $patterns
        );
    }

    public function show(PatternRequest $request): bool|string
    {
        $response = $this->pattern->getPattern($request->getId());
        if(!$response){
            $this->response->response('Not Found', sprintf("Pattern with id [%s] was not found", $request->getId()));
        }
        return $this->response->response('Ok',
            sprintf("Pattern with id [%s] was found", $request->getId()),
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function submit(StorePatternRequest $request)
    {
        $params = $request->getParams();
        if($this->pattern->getPatternByName($request->getPattern()) !== false){
            return $this->response->response('Ok',
                "Pattern is already created and found in database.",
                $this->pattern->getPatternByName($request->getPattern()));
        }
        $this->pattern->submitPattern($params);
        return $this->response->response('Created',
            "Pattern has been created.",
            $this->pattern->getPatternByName($request->getPattern()));
    }

    public function delete(DeletePatternRequest $request): bool|string
    {
        $id = $request->getId();
        if(!$this->pattern->getPattern($id)) {
            return $this->response->response('Not Found',
                sprintf("Pattern with id [%s] has not been found.", $id));
        }
        $this->pattern->deletePattern($id);
        return $this->response->response('Ok',
            sprintf("Pattern with id [%s] has been deleted.", $id));
    }

    public function update(UpdatePatternRequest $request): bool|string|null
    {
        $this->checkIfExists($request);
        try{
            $this->pattern->updatePattern($request->getParams());
            return $this->response->response('Ok',
                sprintf("Pattern with id [%s] has been updated.", $request->getId()));
        }catch(PDOException $e)
        {
            return $this->response->response('Conflict',
                sprintf("Pattern with id [%s] is already created.", $request->getId()));
        }
        return null;
    }

    private function checkIfExists(UpdatePatternRequest $request)
    {
        if(!$this->pattern->getPattern($request->getId())){
            return $this->response->response('Not Found',
                sprintf("Pattern with id [%s] has not been found!", $request->getId()));
        }
    }
}
