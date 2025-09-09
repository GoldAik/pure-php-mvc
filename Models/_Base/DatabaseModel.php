<?php

declare(strict_types = 1);

namespace App\Models\_Base;




use App\Models\_Base\DatabaseDecorator;
use App\Models\_Base\Validator;

class DatabaseModel extends DatabaseDecorator {
    protected static $table;
    protected Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    public static function all(): array
    {
        $query = "SELECT * FROM " . static::$table;
        
        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        }) ?? [];
    }

    public static function find($id): self|false
    {
        $query = "SELECT * FROM " . static::$table . " WHERE id = :id";
        
        return self::executeWithErrorHandling(function() use ($query, $id) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchObject(static::class);
        }) ?? false;
    }

    public static function where($key, $value): array
    {
        $query = "SELECT * FROM " . static::$table . " WHERE $key = :$key";
        
        return self::executeWithErrorHandling(function() use ($query, $key, $value) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(":$key", $value);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        }) ?? [];
    }

    public function delete(): bool
    {
        if(!property_exists($this, 'id'))
            return false;

        $query = "DELETE FROM " . static::$table . " WHERE id = :id";

        return self::executeWithErrorHandling(function() use ($query) {
            $stmt = self::prepareQuery($query);
            $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
            return $stmt->execute();
        }) ?? false;
    }

    public function save(): bool
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

    protected function create(): bool
    {
        $fields = $this->getSelfVars();
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
        }) ?? false;        
    }

    protected function update(): bool
    {
        $fields = $this->getSelfVars();
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
        }) ?? false;
    }

    protected function validate(): bool
    {
        $fields = $this->getSelfVars();

        foreach($fields as $key => $value){

            if(!$this->validator->byRules($value, $key))
                return false;
            
            $method = 'validate'.ucfirst($key);
            if(method_exists($this, $method)){
                if(!$this->$method()){
                    return false;
                }
            }
        }

        return true; 
    }

    public function getValidationErrors(): array
    {
        return $this->validator->getErrors();
    }

    protected function getSelfVars(): array
    {
        $vars = get_object_vars($this);
        unset($vars['validator']);

        return $vars;
    }
}