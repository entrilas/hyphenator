<?php

declare(strict_types=1);

namespace App\Constants;

use Exception;

final class Constants
{
    final public const CONFIG_FILE_NAME = "config.json";
    final public const LOGGER_FILE_NAME = "logger.json";
    final public const DATABASE_FILE_NAME = "database.json";
    final public const MIGRATIONS_FOLDER_NAME = "Migrations";
    final public const CONFIG_PATH = "config";
    final public const EXPORT_FILE_PATH = __DIR__."/../../resources/export.txt";
    final public const MINIMUM_WORD_LENGTH = 4;

    final public const DIR_MODE = 0775;
    final public const FILE_MODE = 0664;
    final public const DEFAULT_TTL = 3600;

    final public const WORD_COMMAND = "word";
    final public const SENTENCE_COMMAND = "sentence";
    final public const FILE_COMMAND = "file";
    final public const MIGRATE_COMMAND = "migrate";
    final public const IMPORT_PATTERNS_COMMAND = "patterns";
}

