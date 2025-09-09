<?php

declare(strict_types = 1);

namespace App\Models;

use App\Models\_Base\DatabaseDecorator;

class User extends DatabaseDecorator{

    public function getAllUsers(): array
    {
        $query = "SELECT * FROM Users";
        
        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->execute();
            return $stmt->fetchAll();
        }) ?? [];
    }
}