<?php

namespace App\Constants;

final class ResponseCodes
{
    final public const NOT_FOUND_ERROR_NAME = "Not Found";
    final public const CONFLICT_ERROR_NAME = "Conflict";
    final public const UNPROCESSABLE_ENTITY_ERROR_NAME = "Unprocessable Entity";
    final public const OK_ERROR_NAME = "Ok";
    final public const CREATED_ERROR_NAME = "Created";

    final public const NOT_FOUND_ERROR_CODE = 404;
    final public const CONFLICT_ERROR_CODE = 409;
    final public const UNPROCESSABLE_ENTITY_ERROR_CODE = 422;
    final public const OK_ERROR_CODE = 200;
    final public const CREATED_ERROR_CODE = 201;
}