<?php

declare(strict_types = 1);

namespace App\Models\_Base;

require_once \APP_PATH . '/Models/_Base/Database.php';

use App\Models\_Base\Database;
use App\Log;

class DatabaseDecorator extends Database{

    protected static function prepareQuery($query)
    {
        $stmt = self::getPDO()->prepare($query);

        if($stmt === false)
            throw new \PDOException("Failed to prepare statement: " . implode(" ", self::getPDO()->errorInfo())); 
        else
            return $stmt;
    }

    protected static function executeWithErrorHandling(callable $callback, ...$params)
    {
        try {
            return $callback(...$params);
            
        } catch (\PDOException $e) {
            if(\DEBUG_MODE)
                throw $e;
            else
                Log::exception($e);

            return null;

        } catch (\Exception $e) {
            if(\DEBUG_MODE)
                throw $e;
            else
                Log::exception($e);

            return null;
        }
    }
}