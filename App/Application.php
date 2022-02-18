<?php

declare(strict_types=1);

namespace App;

use App\Algorithm\DatabaseHyphenation;
use App\Algorithm\FileHyphenation;
use App\Algorithm\HyphenationTrie;
use App\Algorithm\Interfaces\HyphenationInterface;
use App\Algorithm\SentenceHyphenation;
use App\Console\Console;
use App\Console\Validator;
use App\Constants\Constants;
use App\Core\Cache\Cache;
use App\Core\Config;
use App\Core\Database\Database;
use App\Core\Database\Export;
use App\Core\Database\Migration;
use App\Core\Database\Import;
use App\Core\Database\QueryBuilder;
use App\Core\Log\Logger;
use App\Core\Parser\JSONParser;
use App\Core\Router;
use App\Core\Settings;
use App\Core\Timer;
use App\Models\Pattern;
use App\Services\FileExportService;
use App\Services\FileReaderService;
use App\Services\PatternReaderService;
use Cassandra\Time;
use Exception;

class Application
{
    public static Application $app;
    public Config $config;
    public $settings;
    public Cache $cache;
    public FileReaderService $fileReaderService;
    public PatternReaderService $patternReaderService;
    public FileExportService $fileExportService;
    public Timer $timer;
    public Logger $logger;
    public Database $database;
    public Migration $migration;
    public QueryBuilder $queryBuilder;
    public Import $importService;
    public Export $exportService;
    public Validator $validator;
    public HyphenationTrie $hyphenationTrie;
    public DatabaseHyphenation $databaseHyphenation;
    public FileHyphenation $fileHyphenation;
    public SentenceHyphenation $sentenceHyphenation;
    public Console $console;
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->cache = new Cache($this->config);
        $this->fileReaderService = new FileReaderService();
        $this->patternReaderService = new PatternReaderService($this->cache);
        $this->fileExportService = new FileExportService();
        $this->timer = new Timer();
        $this->logger = new Logger($this->config);
        $this->database = new Database($this->config);
        $this->migration = new Migration($this->logger, $this->database);
        $this->queryBuilder = new QueryBuilder($this->database);
        $this->importService = new Import($this->queryBuilder,
            $this->cache,
            $this->fileReaderService
        );
        $this->exportService = new Export($this->queryBuilder, $this->cache);
        $this->validator = new Validator();
        $this->settings = new Settings(
            $this->patternReaderService,
            $this->exportService,
            $this->config
        );
        $this->hyphenationTrie = new HyphenationTrie($this->settings);
        $this->formDatabaseHyphenation();
        $this->formFileHyphenation();
        $this->sentenceHyphenation = new SentenceHyphenation($this->databaseHyphenation);
        $this->formConsole();
    }

    private function formDatabaseHyphenation(): void
    {
        $this->databaseHyphenation = new DatabaseHyphenation(
            $this->hyphenationTrie,
            $this->queryBuilder,
            $this->settings,
            $this->database,
            $this->logger
        );
    }

    private function formFileHyphenation(): void
    {
        $this->fileHyphenation = new FileHyphenation(
            $this->databaseHyphenation,
            $this->fileReaderService
        );
    }

    private function formConsole(): void
    {
        $this->console = new Console(
            $this->config,
            $this->databaseHyphenation,
            $this->fileHyphenation,
            $this->sentenceHyphenation,
            $this->fileExportService,
            $this->importService,
            $this->exportService,
            $this->logger,
            $this->timer,
            $this->migration,
            $this->validator
        );
    }
}
