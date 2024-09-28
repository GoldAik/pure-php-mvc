<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/_Base/DatabaseDecorator.php';

use App\Models\_Base\DatabaseDecorator;

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