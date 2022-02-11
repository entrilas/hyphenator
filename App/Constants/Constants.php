<?php

namespace App\Constants;

use Exception;

final class Constants
{
    final public const CONFIG_FILE_NAME = "config";
    final public const LOGGER_FILE_NAME = "logger";
    final public const CONFIG_PATH = "/config/";
    final public const EXPORT_FILE_PATH = __DIR__."/../../resources/export.txt";

    final public const DIR_MODE = 0775;
    final public const FILE_MODE = 0664;
    final public const DEFAULT_TTL = 3600;
    final public const CACHE_PATH = 'cache';

    final public const WORD_COMMAND = "word";
    final public const SENTENCE_COMMAND = "sentence";
    final public const FILE_COMMAND = "file";

    /**
     * @throws Exception
     */
    private function __construct()
    {
        throw new Exception("Can't get an instance of Constants");
    }
}
