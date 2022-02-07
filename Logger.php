<?php

class Logger
{
    protected static $LOGS = [];

    public static $PRINT_LOG_STATUS = true;

    private static $LOGGER_STATUS = false;

    private static $TIME_TRACKERS = [];

    private static $OUTPUT_STREAMS = [];

    public static function info($message, $name = '')
    {
        return self::add($message, $name, 'info');
    }

    public static function debug($message, $name = '')
    {
        return self::add($message, $name, 'debug' );
    }

    public static function warning($message, $name = '')
    {
        return self::add($message, $name, 'warning' );
    }

    public static function error($message, $name = '')
    {
        return self::add($message, $name, 'error');
    }

    public static function time($name)
    {
        if (!isset(self::$TIME_TRACKERS[$name])) {
            self::$TIME_TRACKERS[$name] = microtime(true);
            return self::$TIME_TRACKERS[$name];
        }
        else {
            return false;
        }
    }

    public static function timeEnd($name)
    {
        if (isset(self::$TIME_TRACKERS[$name])) {
            $start = self::$TIME_TRACKERS[$name];
            $end = microtime(true);
            $elapsedTime = number_format( ( $end - $start), 4);
            unset(self::$TIME_TRACKERS[ $name]);
            self::add("$elapsedTime seconds", "'$name' has been done in", "time frame");
            return $elapsedTime;
        }
        else {
            return false;
        }
    }

    private static function add($message, $name = '', $level = 'debug')
    {
        $logEntry = [
            'timestamp' => time(),
            'name' => $name,
            'message' => $message,
            'level' => $level,
        ];

        self::$LOGS[] = $logEntry;

        if (!self::$LOGGER_STATUS) {
            self::init();
        }

        if (self::$LOGGER_STATUS && count(self::$OUTPUT_STREAMS) > 0)
        {
            $outputLine = self::formatLog($logEntry) . PHP_EOL;
            foreach (self::$OUTPUT_STREAMS as $key => $stream){
                fputs($stream, $outputLine);
            }
        }
        return $logEntry;
    }

    public static function formatLog(array $logEntry)
    {
        $logMessage = "";
        $logMessage .= date('c', $logEntry['timestamp']) . " ";
        $logMessage .= "[" . strtoupper($logEntry['level'] ) . "] : ";
        if (!empty( $logEntry['name'])) {
            $logMessage .= $logEntry['name'] . " => ";
        }
        $logMessage .= $logEntry['message'];

        return $logMessage;
    }

    public static function init() {
        if (!self::$LOGGER_STATUS) {
            if (true === self::$PRINT_LOG_STATUS) {
                self::$OUTPUT_STREAMS[ 'stdout' ] = STDOUT;
            }
        }
        self::$LOGGER_STATUS = true;
    }

}