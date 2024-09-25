<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/DatabaseDecorator.php';

use App\Models\DatabaseDecorator;

class DatabaseModel extends DatabaseDecorator {
    protected static $table;

    public static function all(): array
    {
        $query = "SELECT * FROM " . static::$table;
        
        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        });
    }

    public static function find($id): self|false
    {
        $query = "SELECT * FROM " . static::$table . " WHERE id = :id";
        
        return self::executeWithErrorHandling(function() use ($query, $id) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(static::class);
        });
    }

    public function delete()
    {
        if(!property_exists($this, 'id'))
            return false;

        $query = "DELETE FROM " . static::$table . " WHERE id = :id";

        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            return $stmt->execute();
        });
    }

    public function save()
    {
        if(property_exists($this, 'id') && $this->id) {
            if($this->validate()) 
                return $this->update(); 

        } else {
            if($this->validate()) 
                return $this->create();
        }

        return false;
    }

    protected function create()
    {
        $fields = get_object_vars($this);
        $columns = implode(", ", array_keys($fields));
        $placeholders = ':' . implode(", :", array_keys($fields));
        
        $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        
        return self::executeWithErrorHandling(function() use ($query, $fields) {
            $stmt = self::prepareQuery($query);

            foreach($fields as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            if($stmt->execute()) {
                $this->id = self::getPDO()->lastInsertId();
                return true;
            }
            return false;
        });        
    }

    protected function update()
    {
        $fields = get_object_vars($this);
        unset($fields['id']);

        $set = '';
        foreach($fields as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");

        $query = "UPDATE " . static::$table . " SET $set WHERE id = :id";

        return self::executeWithErrorHandling(function() use ($query, $fields) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);

            foreach($fields as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        });
    }

    protected function validate()
    {
        return true; 
    }
}