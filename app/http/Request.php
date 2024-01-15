<?php

namespace app\http;

class Request
{
    public static function all()
    {
        $input = file_get_contents('php://input');
        return strlen($input) ? json_decode($input, true) : '';
    }

    public static function only(string|array $only)
    {
        $fields = self::all();
        $fieldsKey = array_keys($fields);

        $array = [];
        foreach ($fieldsKey as $index => $value) {
            $onlyFields = is_string($only) ? $only : ($only[$index] ?? null);
            if (!empty($fields[$onlyFields])) {
                $array[$onlyFields] = $fields[$onlyFields];
            }
        }
        return (object)$array;
    }

    public static function except(string|array $excepts)
    {
        $fields = self::all();

        if (is_array($excepts)) {
            foreach ($excepts as $index => $value) {
                unset($fields[$value]);
            }
        }

        if (is_string($excepts)) {
            unset($fields[$excepts]);
        }

        return (object)$fields;
    }

    public static function query(string $name)
    {
        return $_GET[$name] ?? '';
    }
}
