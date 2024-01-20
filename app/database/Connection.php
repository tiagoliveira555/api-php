<?php

namespace app\database;

use PDO;
use PDOException;

class Connection
{
    private static $connection = null;

    public static function connect()
    {
        $host = getenv('HOST');
        $dbName = getenv('DBNAME');
        $port = getenv('PORT');
        $userName = getenv('USERNAME');
        $password = getenv('PASSWORD');

        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbName . ';charset=utf8',
                    $userName,
                    $password,
                    [
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), 500);
            }
        }
        return self::$connection;
    }
}
