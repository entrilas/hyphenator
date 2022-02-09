<?php

namespace App\Constants;

final class Constants
{
    final public const RESOURCES_FILE_PATH = __DIR__.'/../Resources/';
    final public const US_TEX_PATTERNS_FILE_NAME = 'tex-hyphenation-patterns.txt';
    final public const US_TEX_PATTERNS = self::RESOURCES_FILE_PATH . self::US_TEX_PATTERNS_FILE_NAME;
    final public const LARGE_FILE_NAME = 'largetext.txt';
    final public const LARGE_FILE = self::RESOURCES_FILE_PATH . self::LARGE_FILE_NAME;

    private function __construct(){
        throw new Exception("Can't get an instance of Constants");
    }
}
