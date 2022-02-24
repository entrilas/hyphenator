<?php

declare(strict_types=1);

namespace App\Core\Log;

use App\Constants\Constants;
use App\Core\Config;
use App\Core\Exceptions\FileNotFoundException;
use App\Core\Exceptions\ParseException;
use App\Core\Exceptions\UnsupportedFormatException;
use App\Core\Log\Interfaces\LoggerInterface;
use App\Core\Settings;
use Exception;

class Logger implements LoggerInterface
{
    private array $loggerSettings;
    private mixed $openedFile;

    public function __construct(
        private Settings $settings
    ) {
        $this->loggerSettings = $this->settings->getLoggerConfig();
    }

    private function getLogFile(): string
    {
        $time = date($this->loggerSettings['LOG_DATE_FORMAT']);
        return $this->getRoot()
            . $this->loggerSettings['LOG_PATH']
            . DIRECTORY_SEPARATOR
            . "log-$time.txt";
    }

    private function getLogDirectory(): string
    {
        return $this->getRoot()
            . $this->loggerSettings['LOG_PATH'];
    }

    private function getRoot(): string
    {
        return dirname(__FILE__, 4);
    }

    /**
     * @throws Exception
     */
    public function createLogFile(): void
    {
        $logFile = $this->getLogFile();
        $logDirectory = $this->getLogDirectory();

        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, 0777, true);
        }

        if (!file_exists($logFile)) {
            $this->openedFile = fopen($logFile, 'w') or exit("Can't create {static::log_file}!");
        }

        if (!is_writable($logFile)) {
            throw new Exception("ERROR: Unable to write to file!", 1);
        }
    }

    /**
     * @throws Exception
     */
    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * @throws Exception
     */
    public function log($level, $message, array $context = array()): void
    {
        $context = [
            'message' => $message,
            'level' => $level,
            'context' => $context
        ];

        $message = $this->formatLog($context);

        $this->logFile($message);
        $this->logConsole($message);
    }

    private function formatLog($context): string
    {
        $time = date($this->loggerSettings['LOG_FORMAT']);
        $timeLog = is_null($time) ? "[N/A] " : "[$time] ";
        $levelLog = is_null($context['level']) ? "[N/A]" : "[{$context['level']}]";
        $messageLog = is_null($context['message']) ? "N/A" : "{$context['message']}";
        $contextLog = empty($args['context']) ? "" : "$context";
        return "$timeLog $levelLog: - $messageLog $contextLog" . PHP_EOL;
    }

    private function logConsole($message): void
    {
        if($this->loggerSettings['LOG_TO_CONSOLE']) {
            print_r($message);
        }
    }

    /**
     * @throws Exception
     */
    private function logFile($message): void
    {
        $this->createLogFile();

        if (!is_resource($this->getLogFile())) {
            $this->openLog();
        }

        if($this->loggerSettings['LOG_TO_FILE']) {
            fwrite($this->openedFile, $message . PHP_EOL);
        }
        $this->closeFile();
    }

    private function openLog(): void
    {
        $openFile = $this->getLogFile();
        $this->openedFile = fopen($openFile, 'a') or exit("Can't open $openFile!");
    }

    public function closeFile(): void
    {
        if ($this->getLogFile()) {
            fclose($this->openedFile);
        }
    }
}

