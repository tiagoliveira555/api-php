<?php

namespace app\database\models;

use app\database\Connection;
use PDOException;

abstract class Model
{
    protected string $table = '';
    protected $connection = null;

    public function __construct()
    {
        $this->connection = Connection::connect();
    }

    private function execute(string $query, array $values = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($values);
            return $statement;
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), 500);
        }
    }

    public function fetchAll()
    {
    }

    public function insert(array $data)
    {
        $fields = array_keys($data);
        $binds = array_pad([], count($fields), '?');

        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $binds) . ")";

        $this->execute($query, array_values($data));

        return $this->connection->lastInsertId();
    }

    public function update($where, $data)
    {
        $whereKey = array_keys($where)[0];
        $query = "UPDATE {$this->table} SET " . implode(' = ?, ', array_keys($data)) . "= ? WHERE " . $whereKey . " = ?";

        $values = array_merge(array_values($data), array_values($where));

        $this->execute($query, $values);

        return true;
    }

    public function delete($where)
    {
        $whereKey = array_keys($where)[0];
        $query = "DELETE FROM {$this->table} WHERE " . $whereKey . " = ?";

        $this->execute($query, array_values($where));

        return true;
    }
}
