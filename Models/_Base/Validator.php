<?php

declare(strict_types = 1);

namespace App\Models\_Base;

class Validator{
    protected $rules;
    protected $errors = [];

    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    public function byRules($data, $field): bool
    {
        $this->errors[$field] = [];

        $rules = $this->rules[$field] ?? [];

        foreach($rules as $rule => $ruleValue) {
            $method = "validate" . ucfirst($rule);

            if(method_exists($this, $method)) {
                $result = $this->$method($data ?? null, $ruleValue);
                if(!$result) {
                    $this->errors[$field][] = "Failed validation for rule '$rule'";
                }
            }
        }

        return !($this->errors[$field]);
    }

    protected function validateRequired($value): bool
    {
        return !empty($value);
    }

    protected function validateMinLength(string $value, $length): bool
    {
        return strlen($value) >= $length;
    }

    protected function validateMaxLength(string $value, $length): bool
    {
        return strlen($value) <= $length;
    }

    protected function validateEmail(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getErrors(?string $field = null): array
    {
        if($field) return $this->errors[$field] ?? [];
        return $this->errors;
    }

    public function addError(string $field, string $error): void
    {
        $this->errors[$field][] = $error;
    }
}