<?php

declare(strict_types=1);

namespace App;

use App\Algorithm\Hyphenation;
use App\Algorithm\FileHyphenation;
use App\Algorithm\HyphenationTrie;
use App\Algorithm\SentenceHyphenation;
use App\Console\Console;
use App\Console\Validator;
use App\Core\Cache\Cache;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\Database\Export;
use App\Core\Database\Migration;
use App\Core\Database\Import;
use App\Core\Database\QueryBuilder;
use App\Core\Log\Logger;
use App\Core\Settings;
use App\Core\Timer;
use App\Models\Pattern;
use App\Models\ValidPattern;
use App\Models\Word;
use App\Services\FileExportService;
use App\Services\FileReaderService;
use App\Services\PatternReaderService;
use Exception;

class Application
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $config = new Config();
        $cache = new Cache($config);
        $fileReaderService = new FileReaderService();
        $patternReaderService = new PatternReaderService($cache);
        $fileExportService = new FileExportService();
        $timer = new Timer();
        $logger = new Logger($config);
        $database = new Database($config);
        $migration = new Migration($logger, $database);
        $queryBuilder = new QueryBuilder($database);
        $importService = new Import(
            $queryBuilder,
            $cache,
            $fileReaderService
        );
        $exportService = new Export($queryBuilder, $cache);
        $validator = new Validator();
        $settings = new Settings(
            $patternReaderService,
            $exportService,
            $config,
            $logger
        );
        $patterns = $settings->getPatterns();
        $hyphenationTrie = new HyphenationTrie($patterns);
        $word = new Word($queryBuilder);
        $pattern = new Pattern($queryBuilder);
        $validPattern = new ValidPattern($queryBuilder);
        $databaseHyphenation = new Hyphenation(
            $hyphenationTrie,
            $word,
            $pattern,
            $validPattern,
            $settings,
            $database,
            $logger
        );
        $fileHyphenation = new FileHyphenation(
            $databaseHyphenation,
            $fileReaderService
        );
        $sentenceHyphenation = new SentenceHyphenation($databaseHyphenation);
        $console = new Console(
            $config,
            $databaseHyphenation,
            $fileHyphenation,
            $sentenceHyphenation,
            $fileExportService,
            $importService,
            $exportService,
            $logger,
            $timer,
            $migration,
            $validator
        );
        $console->runConsole();
    }
}
