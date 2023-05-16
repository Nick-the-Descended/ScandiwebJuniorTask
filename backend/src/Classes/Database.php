<?php

namespace src\Classes;
class Database
{
    private \mysqli $connection;

    public function __construct()
    {
        $this->connection = new \mysqli(HOST, USERNAME, PASSWORD, DATABASE);

        if ($this->connection->connect_error) {
            throw new \Exception("Connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    /**
     * @throws \Exception
     */
    public function execute(string $query): \mysqli_result|bool
    {
        $result = $this->connection->query($query);

        if ($this->connection->error) {
            throw new \Exception("Database error: " . $this->connection->error);
        }

        return $result;
    }

    public function escape_string(mixed $value): string
    {
        return $this->connection->escape_string($value);
    }

    /**
     * @throws \Exception
     */
    public function prepare(string $query): \mysqli_stmt
    {
        $stmt = $this->connection->prepare($query);

        return !$stmt ? throw new \Exception("Failed to prepare statement: " . $this->connection->error) : $stmt;

    }
}