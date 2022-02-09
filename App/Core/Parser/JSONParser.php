<?php

namespace App\Core\Parser;

class JSONParser
{
    public function parse($path)
    {
        $data = json_decode(file_get_contents($path), true);

        if (function_exists('json_last_error_msg')) {
            $error_message = json_last_error_msg();
        } else {
            $error_message  = 'Syntax error';
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = array(
                'message' => $error_message,
                'type'    => json_last_error(),
                'file'    => $path,
            );

            throw new ParseException($error);
        }

        return $data;
    }

    public function extension()
    {
        return array('json');
    }
}