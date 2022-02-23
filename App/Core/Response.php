<?php

namespace App\Core;

class Response
{
    public string $status;
    public string $message;
    public array $data = [];
    public int $statusCode;
    public array $result;

    public function response(string $status, string $message = '', array $data = []): void
    {
        $this->formResult($status, $message, $data);
        switch ($this->status) {
            case "Not Found";
                $this->statusCode = 404;
                break;
            case "Conflict":
                $this->statusCode = 409;
                break;
            case "Unprocessable Entity":
                $this->statusCode = 422;
                break;
            case "Ok":
                $this->statusCode = 200;
                break;
            case "Created":
                $this->statusCode = 201;
                break;
        }
        $this->setHeader($this->statusCode, $this->status);
        $this->makeMessage();

        echo json_encode($this->result);
        exit();
    }

    private function setHeader(int $statusCode, string $status): void
    {
        header("Content-Type: application/json");
        header(sprintf('HTTP/1.1 %s %s', $statusCode, $status), true, $statusCode);
    }

    private function formResult(string $status, string $message, array $data): void
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;

        $this->result = array(
            'status' => $this->status
        );
    }

    private function makeMessage(): void
    {
        if ($this->message != ''){
            $this->result['message'] = $this->message;
        }

        if (count($this->data) > 0){
            $this->result['data'] = $this->data;
        }
    }
}