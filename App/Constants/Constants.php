<?php

declare(strict_types=1);

namespace App\Constants;

final class Constants
{
    public const CONFIG_FILE_NAME = 'config.json';
    public const LOGGER_FILE_NAME = 'logger.json';
    public const DATABASE_FILE_NAME = 'database.json';
    public const MIGRATIONS_FOLDER_NAME = 'Migrations';
    public const CACHE_OUTPUT_NAME = 'CACHE_OUTPUT';
    public const PATTERNS_PATH_NAME = 'PATTERNS_PATH';
    public const CONFIG_PATH = '/config/';

    public const MINIMUM_WORD_LENGTH = 4;
    public const PAGE_LIMIT = 10;

    public const DIR_MODE = 0777;
    public const FILE_MODE = 0664;
    public const DEFAULT_TTL = 3600;

    public const WORD_COMMAND = 'word';
    public const SENTENCE_COMMAND = 'sentence';
    public const FILE_COMMAND = 'file';
    public const MIGRATE_COMMAND = 'migrate';
    public const IMPORT_PATTERNS_COMMAND = 'patterns';
}
