<?php

declare(strict_types = 1);

namespace App\Models\_Base;

class Database{

    private static ?\PDO $pdo = null;

    private static function connectDatabase(): \PDO
    {
        try{
            if(file_exists(\APP_PATH . '/config/database.php'))
                require_once \APP_PATH . '/config/database.php';
            else
                throw new \RuntimeException('Config file not found');

            $dbConfig = $config['connection'] ?? throw new \RuntimeException('Config hasnt connection data');
            $dbConfig['host'] ?? throw new \RuntimeException('Config hasnt connection data');
            $dbConfig['name'] ?? throw new \RuntimeException('Config hasnt connection data');
            $dbConfig['user'] ?? throw new \RuntimeException('Config hasnt connection data');
            $dbConfig['password'] ?? throw new \RuntimeException('Config hasnt connection data');

            
            if(!$dbConfig) throw new \RuntimeException('Config hasnt connection data');

            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset=utf8";

            return new \PDO($dsn, $dbConfig['user'], $dbConfig['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false
            ]);

        }catch(\PDOException $e){
            error_log($e->getMessage());
            throw new \RuntimeException('Database connection failed');
        }
    }

    protected static function getPDO(): \PDO
    {
        if(self::$pdo === null){
            self::$pdo = self::connectDatabase();
        }

        return self::$pdo;
    }
}