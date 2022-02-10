<?php

declare(strict_types=1);

namespace App\Core\Parser;

use App\Core\Exceptions\ParseException;

class JSONParser
{
    /**
     * @throws ParseException
     */
    public function parse($path)
    {
        $parsedData = json_decode(file_get_contents($path), true);
        $this->validateResponse($path);
        return $parsedData;
    }

    /**
     * @throws ParseException
     */
    private function validateResponse($path)
    {
        if (function_exists('json_last_error_msg')) {
            $error_message = json_last_error_msg();
        } else {
            $error_message  = 'Syntax Error';
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = array(
                'message' => $error_message,
                'type'    => json_last_error(),
                'file'    => $path,
            );
            throw new ParseException($error);
        }
    }
}