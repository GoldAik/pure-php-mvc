<?php

declare(strict_types = 1);

namespace App;

require_once \APP_PATH . '/Log.php';

use App\Log;

class ErrorHandler
{
    public function __construct() {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $errorMessage = "Error: [$errno] $errstr - $errfile:$errline";

        switch ($errno) {
            case \E_ERROR:
                Log::error($errorMessage);
                break;
            case \E_WARNING:
                Log::warning($errorMessage);
                break;
            case \E_NOTICE:
                Log::info($errorMessage);
                break;
            default:
                Log::error($errorMessage);
                break;
        }

        if(\DEBUG_MODE)
            echo $errorMessage;
        else
            return true;
    }

    public function handleException(\Throwable $exception)
    {
        $errorMessage = "Exception: " . $exception->getMessage() . " in " . $exception->getFile() . ":" . $exception->getLine();
        Log::throwable($exception);
        
        if(\DEBUG_MODE)
            echo $errorMessage;
        else
            return true;
    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== null) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}