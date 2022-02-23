<?php

namespace App\Core;

use App\Constants\ResponseCodes;

class Response
{
    public string $status;
    public string $message;
    public array $data = [];
    public int $statusCode;
    public array $result;

    public function response(string $status, string $message = '', array $data = []): string|null
    {
        $this->formResult($status, $message, $data);
        switch ($this->status) {
            case ResponseCodes::NOT_FOUND_ERROR_NAME;
                $this->statusCode = ResponseCodes::NOT_FOUND_ERROR_CODE;
                break;
            case ResponseCodes::CONFLICT_ERROR_NAME:
                $this->statusCode = ResponseCodes::CONFLICT_ERROR_CODE;
                break;
            case ResponseCodes::UNPROCESSABLE_ENTITY_ERROR_NAME:
                $this->statusCode = ResponseCodes::UNPROCESSABLE_ENTITY_ERROR_CODE;
                break;
            case ResponseCodes::OK_ERROR_NAME:
                $this->statusCode = ResponseCodes::OK_ERROR_CODE;
                break;
            case ResponseCodes::CREATED_ERROR_NAME:
                $this->statusCode = ResponseCodes::CREATED_ERROR_CODE;
                break;
        }
        $this->setHeader($this->statusCode, $this->status);
        $this->makeMessage();
        $jsonMessage = json_encode($this->result);
        echo json_encode($this->result);
        return $jsonMessage;
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

        $this->result = [
            'status' => $this->status
        ];
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