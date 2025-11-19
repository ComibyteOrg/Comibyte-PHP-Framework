<?php
namespace App\Database;

use mysqli;

class MySQLConnection implements DatabaseInterface
{
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    private $port;

    public function __construct(string $host, string $username, string $password, string $database, int $port = 3306)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    public function connect()
    {
        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database,
            $this->port
        );

        if ($this->connection->connect_error) {
            throw new \Exception("Connection failed: " . $this->connection->connect_error);
        }

        return $this->connection;
    }

    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function query($query, $params = [])
    {
        if (empty($params)) {
            return $this->connection->query($query);
        }

        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            throw new \Exception("Query preparation failed: " . $this->connection->error);
        }

        // Determine types string for bind_param
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            elseif (is_string($param)) $types .= 's';
            else $types .= 'b';
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        
        return $stmt->get_result();
    }

    public function lastInsertId()
    {
        return $this->connection->insert_id;
    }

    public function beginTransaction()
    {
        return $this->connection->begin_transaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollback()
    {
        return $this->connection->rollback();
    }
}