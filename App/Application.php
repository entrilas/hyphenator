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
use App\Core\DI\Container;
use App\Core\Log\Logger;
use App\Core\Patterns;
use App\Core\Timer;
use App\Models\Pattern;
use App\Models\ValidPattern;
use App\Models\Word;
use App\Services\DataExportService;
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
        if(PHP_SAPI === "cli")
        {
            $container = new Container;
            $console = $container->get('App\\Console\\Console');
            $console->runConsole();
        }else{
            require_once __DIR__.'/../App/Routes.php';
        }
    }
}
