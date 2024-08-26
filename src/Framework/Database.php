<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

class Database
{
    private PDO $connection;
    private PDOStatement $stmt;

    public function __construct(string $driver, array $config, string $username, string $password)
    {
        $config = http_build_query(data: $config, arg_separator: ';');
        $dsn = "{$driver}:{$config}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC
            ]); // 4th arg is an array of config detail, the fetch mode can config in this array
        } catch (PDOException $e) {
            die("Unable to connect to database");
        }
    }

    public function query(string $query)
    {
        $this->connection->query($query);
    }

    public function prepare(string $query, array $params = []): Database
    {
        $this->stmt = $this->connection->prepare($query);

        $this->stmt->execute($params);

        return $this;
    }

    public function count()
    {
        return $this->stmt->fetchColumn();
    }

    public function find()
    {
        return $this->stmt->fetch(); // result query as an array however it doesnt return a result with numeric and name index, as we know we can configure a FETCH MODE, the another solution is providing configuration to our PDO instance 
    }

    public function get_last_id()
    {
        return $this->connection->lastInsertId();
    }

    public function getAll()
    {
        return $this->stmt->fetchAll();
    }
}
