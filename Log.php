<?php

declare(strict_types = 1);

namespace App;

class Log{
    const LEVEL_INFO = 'INFO';
    const LEVEL_WARNING = 'WARNING';
    const LEVEL_ERROR = 'ERROR';

    private static function log(string $level, string $message)
    {
        $filename = 'logs-'. date('Y-m') .'.log';
        $dirPath = \LOG_PATH;

        $date = date('d.m.Y H:i:s');
        $formattedMessage = "[$date] $level | $message\n";

        if(!is_dir($dirPath)){
            if(!mkdir($dirPath, 0755, true)){
                if(\DEBUG_MODE)
                    throw new \RuntimeException('Unable to create directory for logs');
                else
                    return;
            }
        }

        file_put_contents($dirPath . "/$filename", $formattedMessage, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message): void
    {
        self::log(self::LEVEL_INFO, $message);
    }

    public static function warning(string $message): void
    {
        self::log(self::LEVEL_WARNING, $message);
    }

    public static function error(string $message): void
    {
        self::log(self::LEVEL_ERROR, $message);
    }

    public static function exception(\Exception $e): void
    {
        $message = sprintf(
            "Exception: %s in %s:%d\nStack trace:\n%s",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        self::error($message);
    }

    public static function throwable(\Throwable $e): void
    {
        $message = sprintf(
            "Exception: %s in %s:%d\nStack trace:\n%s",
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        self::error($message);
    }
}