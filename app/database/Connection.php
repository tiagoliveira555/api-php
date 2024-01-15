<?php

namespace app\database;

use PDO;
use PDOException;

class Connection
{
    private static $connection = null;

    private const HOST = '127.0.0.1';
    private const DBNAME = 'api_php';
    private const PORT = 3306;
    private const USERNAME = 'root';
    private const PASSWORD = 'root';

    public static function connect()
    {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    'mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DBNAME . ';charset=utf8',
                    self::USERNAME,
                    self::PASSWORD,
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
