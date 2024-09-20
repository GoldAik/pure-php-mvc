<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/DatabaseDecorator.php';

use App\Models\DatabaseDecorator;

class User extends DatabaseDecorator{

    public function getAllUsers(): array
    {
        return self::executeWithErrorHandling(function() {
            $sql = "SELECT * FROM Users";
            $stmt = self::getPDO()->prepare($sql);

            if($stmt === false)
                throw new \PDOException("Failed to prepare statement: " . implode(" ", self::getPDO()->errorInfo())); 

            $stmt->execute([]);
            return $stmt->fetchAll();
        });
    }
}