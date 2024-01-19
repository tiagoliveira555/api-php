<?php

namespace app\database\models;

use app\database\Connection;
use PDO;
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

    public function pagination(array $paginate)
    {
        $query = "SELECT * FROM {$this->table} LIMIT :offset,:limit";

        try {
            $statement = $this->connection->prepare($query);
            $statement->bindParam(':offset', $paginate['offset'], PDO::PARAM_INT);
            $statement->bindParam(':limit', $paginate['limit'], PDO::PARAM_INT);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), 500);
        }
    }

    public function count()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";

        $response = $this->execute($query);

        return $response->fetchObject()->count;
    }

    public function findAll()
    {
        $query = "SELECT * FROM {$this->table}";

        $response = $this->execute($query);

        return $response->fetchAll(PDO::FETCH_CLASS);
    }

    public function findBy(array $where)
    {
        $whereKey = array_keys($where)[0];

        $query = "SELECT * FROM {$this->table} WHERE " . $whereKey . " = ?";

        $response = $this->execute($query, array_values($where));

        return $response->fetchObject();
    }

    public function insert(array $data)
    {
        $fields = array_keys($data);
        $binds = array_pad([], count($fields), '?');

        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $binds) . ")";

        $this->execute($query, array_values($data));

        $lastId = $this->connection->lastInsertId();

        return $this->findBy(['id' => $lastId]);
    }

    public function update($where, $data)
    {
        $whereKey = array_keys($where)[0];
        $query = "UPDATE {$this->table} SET " . implode(' = ?, ', array_keys($data)) . "= ? WHERE " . $whereKey . " = ?";

        $values = array_merge(array_values($data), array_values($where));

        $this->execute($query, $values);

        return $this->findBy($where);
    }

    public function delete($where)
    {
        $whereKey = array_keys($where)[0];
        $query = "DELETE FROM {$this->table} WHERE " . $whereKey . " = ?";

        $this->execute($query, array_values($where));

        return true;
    }
}
