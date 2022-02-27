<?php

declare(strict_types=1);

namespace App\Constants;

final class ResponseCodes
{
    public const NOT_FOUND_ERROR_NAME = "Not Found";
    public const CONFLICT_ERROR_NAME = "Conflict";
    public const UNPROCESSABLE_ENTITY_ERROR_NAME = "Unprocessable Entity";
    public const OK_ERROR_NAME = "Ok";
    public const CREATED_ERROR_NAME = "Created";

    public const NOT_FOUND_ERROR_CODE = 404;
    public const CONFLICT_ERROR_CODE = 409;
    public const UNPROCESSABLE_ENTITY_ERROR_CODE = 422;
    public const OK_ERROR_CODE = 200;
    public const CREATED_ERROR_CODE = 201;
}
