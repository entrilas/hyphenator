<?php

namespace App\Core\Log;

use App\Core\Log\Interfaces\LoggerInterface;

class Logger implements LoggerInterface
{
    protected static $logFile;

    protected static $logToFileStatus = true;

    protected static $logToConsoleStatus = false;

    protected static $file;

    protected static $options = [
        'dateFormat' => 'd-M-Y',
        'logFormat' => 'H:i:s d-M-Y'
    ];

//    private static $instance;

    public static function createLogFile()
    {
        $time = date(static::$options['dateFormat']);
        static::$logFile = __DIR__ . "/logs/log-{$time}.txt";


        if (!file_exists(__DIR__ . '/logs')) {
            mkdir(__DIR__ . '/logs', 0777, true);
        }

        if (!file_exists(static::$logFile)) {
            fopen(static::$logFile, 'w') or exit("Can't create {static::log_file}!");
        }

        if (!is_writable(static::$logFile)) {
            throw new Exception("ERROR: Unable to write to file!", 1);
        }
    }

    public static function setOptions($options = []) {
        static::$options = array_merge(static::$options, $options);
    }

    public static function info($message, array $context = []) {
        static::log(LogLevel::INFO, $message, $context);
    }

    public static function alert($message, array $context = []) {
        static::log(LogLevel::ALERT, $message, $context);
    }

    public static function critical($message, array $context = []) {
        static::log(LogLevel::CRITICAL, $message, $context);
    }

    public static function notice($message, array $context = []) {
        static::log(LogLevel::NOTICE, $message, $context);
    }

    public static function debug($message, array $context = []) {
        static::log(LogLevel::DEBUG, $message, $context);
    }

    public static function warning($message, array $context = []){
        static::log(LogLevel::WARNING, $message, $context);
    }

    public static function error($message, array $context = []) {
        static::log(LogLevel::ERROR, $message, $context);
    }

    public static function emergency($message, array $context = []) {
        static::log(LogLevel::EMERGENCY, $message, $context);
    }

    public static function log($level, $message, array $context = array()) {

        $context = [
            'message' => $message,
            'level' => $level,
            'context' => $context
        ];

        $message = self::formatLog($context);

        self::logFile($message);
        self::logConsole($message);
    }

    private static function formatLog($context)
    {
        $time = date(static::$options['logFormat']);
        $timeLog = is_null($time) ? "[N/A] " : "[{$time}] ";
        $levelLog = is_null($context['level']) ? "[N/A]" : "[{$context['level']}]";
        $messageLog = is_null($context['message']) ? "N/A" : "{$context['message']}";
        $contextLog = empty($args['context']) ? "" : "{$context}";
        $message = "{$timeLog} {$levelLog}: - {$messageLog} {$contextLog}" . PHP_EOL;
        return $message;
    }

    private static function logConsole($message){
        if(self::$logToConsoleStatus) {
            print_r($message);
        }
    }

    private static function logFile($message){
        static::createLogFile();

        if (!is_resource(static::$file)) {
            static::openLog();
        }

        if(self::$logToFileStatus) {
            fwrite(static::$file, $message . PHP_EOL);
        }
        static::closeFile();
    }

    private static function openLog()
    {
        $openFile = static::$logFile;
        static::$file = fopen($openFile, 'a') or exit("Can't open $openFile!");
    }

    public static function closeFile()
    {
        if (static::$file) {
            fclose(static::$file);
        }
    }

//    public function __wakeup(){
//        throw new \Exception("Cannot unserialize a singleton.");
//    }

//    public static function getInstance(){
//        if (is_null(self::$instance)) {
//            self::$instance = new self();
//        }
//        return self::$instance;
//    }

}
