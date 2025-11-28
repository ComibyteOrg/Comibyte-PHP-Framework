<?php
namespace App\Helper;

use App\Database\Connect;
use PDO;

class DB extends Connect
{
    protected static $table;
    protected static $select = '*';
    protected static $joins = [];
    protected static $where = [];
    protected static $order = '';
    protected static $group = '';
    protected static $limit = '';
    protected static $offset = '';

    public bool $debug = false;

    public function __construct()
    {
        parent::__construct();
    }

    // Reset after each query
    protected static function reset()
    {
        self::$table = null;
        self::$select = '*';
        self::$joins = [];
        self::$where = [];
        self::$order = '';
        self::$group = '';
        self::$limit = '';
        self::$offset = '';
    }

    // 🔥 Shortcuts: DB::users()
    public static function __callStatic($table, $args)
    {
        return self::table($table);
    }

    public static function table($table)
    {
        $instance = new static();
        self::$table = $table;
        return $instance;
    }

    public function debug()
    {
        $this->debug = true;
        return $this;
    }

    // SELECT
    public function select($columns)
    {
        self::$select = $columns;
        return $this;
    }

    // WHERE
    public function where($column, $value, $operator = '=')
    {
        self::$where[] = ['AND', $column, $operator, $value];
        return $this;
    }

    public function orWhere($column, $value, $operator = '=')
    {
        self::$where[] = ['OR', $column, $operator, $value];
        return $this;
    }

    public function whereNull($column)
    {
        self::$where[] = ['AND', "$column IS NULL"];
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        self::$where[] = ['AND', "$column IN ($placeholders)", 'IN', $values];
        return $this;
    }

    // ORDER BY
    public function orderBy($column, $direction = 'ASC')
    {
        self::$order = " ORDER BY $column $direction ";
        return $this;
    }

    // GROUP BY
    public function groupBy($column)
    {
        self::$group = " GROUP BY $column ";
        return $this;
    }

    // LIMIT
    public function limit($limit)
    {
        self::$limit = " LIMIT $limit ";
        return $this;
    }

    public function offset($offset)
    {
        self::$offset = " OFFSET $offset ";
        return $this;
    }

    // JOIN
    public function join($table, $first, $operator, $second)
    {
        self::$joins[] = " JOIN $table ON $first $operator $second ";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second)
    {
        self::$joins[] = " LEFT JOIN $table ON $first $operator $second ";
        return $this;
    }

    // RAW Queries
    public function raw($sql)
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // WHERE builder
    protected function buildWhere(&$bindValues)
    {
        if (empty(self::$where))
            return '';

        $sql = " WHERE ";
        $conditions = [];

        foreach (self::$where as $w) {
            if (count($w) == 2) { // NULL
                $conditions[] = $w[1];
            } elseif ($w[2] === 'IN') {
                $conditions[] = "{$w[1]}";
                foreach ($w[3] as $v) {
                    $bindValues[] = $v;
                }
            } else {
                $conditions[] = "{$w[0]} {$w[1]} {$w[2]} ?";
                $bindValues[] = $w[3];
            }
        }

        return $sql . implode(' ', $conditions);
    }

    // Build query
    protected function buildQuery(&$bindValues)
    {
        $where = $this->buildWhere($bindValues);
        $joins = implode('', self::$joins);

        $sql = "SELECT " . self::$select . " FROM " . self::$table . " "
            . $joins . " "
            . $where . " "
            . self::$group
            . self::$order
            . self::$limit
            . self::$offset;

        return $sql;
    }

    // Execute query
    protected function run($sql, $bindValues)
    {
        if ($this->debug) {
            echo "SQL: $sql\n";
            print_r($bindValues);
        }

        $stmt = $this->connection->prepare($sql);

        foreach ($bindValues as $i => $value) {
            $stmt->bindValue($i + 1, $value);
        }

        $stmt->execute();
        return $stmt;
    }

    // FETCH ALL
    public function get()
    {
        $bindValues = [];
        $sql = $this->buildQuery($bindValues);
        $stmt = $this->run($sql, $bindValues);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        self::reset();
        return $data;
    }

    // FETCH FIRST ROW
    public function first()
    {
        $this->limit(1);
        $data = $this->get();
        return $data[0] ?? null;
    }

    // FIND BY ID
    public function find($id)
    {
        return $this->where('id', $id)->first();
    }

    // COUNT
    public function count()
    {
        self::$select = "COUNT(*) as count";
        return $this->first()['count'] ?? 0;
    }

    // EXISTS
    public function exists()
    {
        return $this->count() > 0;
    }

    // PLUCK
    public function pluck($column)
    {
        self::$select = $column;
        $data = $this->get();
        return array_column($data, $column);
    }

    // AGGREGATES
    public function sum($column)
    {
        self::$select = "SUM($column) as total";
        return $this->first()['total'];
    }

    public function avg($column)
    {
        self::$select = "AVG($column) as avg";
        return $this->first()['avg'];
    }

    public function min($column)
    {
        self::$select = "MIN($column) as min";
        return $this->first()['min'];
    }

    public function max($column)
    {
        self::$select = "MAX($column) as max";
        return $this->first()['max'];
    }

    // PAGINATION
    public function paginate($perPage, $page)
    {
        $offset = ($page - 1) * $perPage;
        $this->limit($perPage)->offset($offset);
        return $this->get();
    }

    // INSERT
    public function insert($data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO " . self::$table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->connection->prepare($sql);

        $i = 1;
        foreach ($data as $value) {
            $stmt->bindValue($i++, $value);
        }

        $stmt->execute();
        $id = $this->connection->lastInsertId();

        self::reset();
        return $id;
    }

    // UPDATE
    public function update($data, $where)
    {
        $set = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));

        $instance = new static();
        foreach ($where as $c => $v) {
            $instance->where($c, $v);
        }

        $bindValues = array_values($data);
        $whereClause = $instance->buildWhere($bindValues);

        $sql = "UPDATE " . self::$table . " SET $set $whereClause";

        $stmt = $this->run($sql, $bindValues);

        self::reset();
        return true;
    }

    // DELETE
    public function delete($where)
    {
        $instance = new static();
        foreach ($where as $c => $v) {
            $instance->where($c, $v);
        }

        $bindValues = [];
        $whereClause = $instance->buildWhere($bindValues);

        $sql = "DELETE FROM " . self::$table . $whereClause;

        $stmt = $this->run($sql, $bindValues);

        self::reset();
        return true;
    }

    // TRANSACTIONS
    public function transaction(callable $callback)
    {
        try {
            $this->connection->beginTransaction();
            $callback($this);
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
