<?php

namespace App\Constants;

final class Constants
{
    const PATTERNS_FILE_PATH = __DIR__.'/../Resources/';
    const US_TEX_PATTERNS_FILE_NAME = 'tex-hyphenation-patterns.txt';
    const US_TEX_PATTERNS = self::PATTERNS_FILE_PATH . self::US_TEX_PATTERNS_FILE_NAME;

    private function __construct(){
        throw new Exception("Can't get an instance of Constants");
    }
}