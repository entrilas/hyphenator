<?php

declare(strict_types=1);

namespace App\Core;

use App\Constants\ResponseCodes;

class Response
{
public string $status;
public string $message;
public $data = [];
public int $statusCode;
public array $result;

public function response(string $status, string $message = '', $data = []): string|null
{
    $this->formResult($status, $message, $data);
    match ($this->status){
        ResponseCodes::NOT_FOUND_ERROR_NAME => $this->statusCode = ResponseCodes::NOT_FOUND_ERROR_CODE,
        ResponseCodes::CONFLICT_ERROR_NAME => $this->statusCode = ResponseCodes::CONFLICT_ERROR_CODE,
        ResponseCodes::UNPROCESSABLE_ENTITY_ERROR_NAME => $this->statusCode = ResponseCodes::UNPROCESSABLE_ENTITY_ERROR_CODE,
        ResponseCodes::CREATED_ERROR_NAME => $this->statusCode = ResponseCodes::CREATED_ERROR_CODE,
    default => $this->statusCode = ResponseCodes::OK_ERROR_CODE,
    };
        $this->setHeader($this->statusCode, $this->status);
        $this->makeMessage();
        $jsonMessage = json_encode($this->result);
        echo json_encode($this->result);
            return $jsonMessage;
        }

    private function setHeader(int $statusCode, string $status): void
    {
        header("Content-Type: application/json");
            header(sprintf('HTTP/1.1 %s %s', $statusCode, $status), true, $statusCode);
    }

    private function formResult(string $status, string $message, $data): void
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
        if ($this->message != '') {
            $this->result['message'] = $this->message;
        }
        if (count($this->data) > 0) {
            $this->result['data'] = $this->data;
        }
    }
}
