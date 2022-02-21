<?php

namespace App\Requests;

class DeleteWordRequest
{
    public function __construct(private $id)
    {
        $this->validateDelete($id);
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

    public function getId(): array
    {
        return $this->id;
    }
}