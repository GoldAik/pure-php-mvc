<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/DatabaseDecorator.php';

use App\Models\DatabaseDecorator;

class User extends DatabaseDecorator{

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM Users";
        
        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->execute();
            return $stmt->fetchAll();
        });
    }
}