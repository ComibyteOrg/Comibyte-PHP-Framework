<?php
namespace App\Database;

use SQLite3;

class SQLiteConnection implements DatabaseInterface
{
    private $connection;
    private $database;

    public function __construct(string $database)
    {
        $this->database = $database;
    }

    public function connect()
    {
        $this->connection = new SQLite3($this->database);
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
        $stmt = $this->connection->prepare($query);
        
        if (!$stmt) {
            throw new \Exception("Query preparation failed: " . $this->connection->lastErrorMsg());
        }

        foreach ($params as $key => $value) {
            $paramNum = $key + 1;
            if (is_int($value)) {
                $stmt->bindValue($paramNum, $value, SQLITE3_INTEGER);
            } elseif (is_float($value)) {
                $stmt->bindValue($paramNum, $value, SQLITE3_FLOAT);
            } else {
                $stmt->bindValue($paramNum, $value, SQLITE3_TEXT);
            }
        }

        return $stmt->execute();
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertRowID();
    }

    public function beginTransaction()
    {
        return $this->connection->exec('BEGIN TRANSACTION');
    }

    public function commit()
    {
        return $this->connection->exec('COMMIT');
    }

    public function rollback()
    {
        return $this->connection->exec('ROLLBACK');
    }
}