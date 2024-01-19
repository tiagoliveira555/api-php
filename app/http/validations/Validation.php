<?php

namespace app\http\validations;

use app\http\Request;
use Exception;

class Validation
{
    public static $errors = [];

    public static function validate(array $validationFields)
    {
        foreach ($validationFields as $field => $validations) {
            if (is_array($validations)) {
                foreach ($validations as $validation) {
                    [$validation, $param] = self::parseValidation($validation);

                    if (method_exists(Validation::class, $validation)) {
                        self::$validation($field, $param);
                    } else {
                        self::$errors[$field] = 'validation ' . $validation . ' does not exist for field ' . $field;
                    }
                }
            } else {
                [$validation, $param] = self::parseValidation($validations);

                if (method_exists(Validation::class, $validation)) {
                    self::$validation($field, $param);
                } else {
                    self::$errors[$field] = 'validation ' . $validation . ' does not exist for field ' . $field;
                }
            }
        }

        return self::$errors;
    }

    private static function parseValidation(string $validation)
    {
        return str_contains($validation, ':') ? explode(':', $validation, 2) : [$validation, ''];
    }

    public static function required(string $field)
    {
        $data = Request::input($field);

        if (strlen($data) === 0 && empty(self::$errors[$field])) {
            self::$errors[$field] = $field . ' is required';
        }
    }

    public static function email(string $field)
    {
        $email = Request::input($field);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && empty(self::$errors[$field])) {
            self::$errors[$field] = 'email invalid';
        }
    }

    public static function min(string $field, string $param)
    {
        $data = Request::input($field);

        if (!is_numeric($param)) {
            throw new Exception('parameter for min must be a numeric value');
        }

        $param = (int)$param;

        if (strlen($data) < $param && empty(self::$errors[$field])) {
            self::$errors[$field] = "{$field} must be at least {$param} characters long";
        }
    }

    public static function max(string $field, string $param)
    {
        $data = Request::input($field);

        if (!is_numeric($param)) {
            throw new Exception('parameter for max must be a numeric value');
        }

        $param = (int)$param;

        if (strlen($data) > $param && empty(self::$errors[$field])) {
            self::$errors[$field] = 'maximum allowed characters for ' . $field . ' is ' . $param;
        }
    }
}
