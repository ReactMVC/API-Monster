<?php

namespace Monster\App\Models;

/**
 * Class DB provides an easy-to-use interface for basic MySQL database operations.
 */
class DB
{
    /** @var string $host the database host */
    private $host;

    /** @var string $username the database username */
    private $username;

    /** @var string $password the database password */
    private $password;

    /** @var string $database the database name */
    private $database;

    /** @var string $charset the database charset */
    private $charset;

    /** @var PDO $pdo the PDO object for database access */
    private $pdo;

    /**
     * Constructs a new DB instance with the given database connection parameters.
     *
     * @param string $host the database host
     * @param string $database the database name
     * @param string $username the database username
     * @param string $password the database password
     * @param string $charset the database charset (default: utf8)
     *
     * @throws \PDOException if the connection to the database fails
     */
    public function __construct($host, $database, $username, $password, $charset = 'utf8')
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        // Set up the PDO object with the given parameters
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
        $options = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        );
        try {
            $this->pdo = new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Inserts a new row into the specified table with the given data.
     *
     * @param string $table the name of the table
     * @param array $data an associative array of column names and values
     *
     * @return bool true if the insert was successful, false otherwise
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Retrieves rows from the specified table that match the specified criteria.
     *
     * @param string $table the name of the table
     * @param string|array $columns the names of the columns to retrieve (default: *)
     * @param array $conditions an associative array of conditions for the query (default: array())
     * @param array $options an associative array of options for the query (default: array())
     *
     * @return array an array of rows that match the query criteria
     */
    public function select($table, $columns = '*', $conditions = array(), $options = array())
    {
        $sql = "SELECT $columns FROM $table";
        $where = '';
        $bindings = array();
        if (!empty($conditions)) {
            $whereArray = array();
            foreach ($conditions as $column => $value) {
                if (is_array($value)) {
                    $operator = key($value);
                    if ($operator === 'LIKE') {
                        $value = "%{$value[$operator]}%";
                    }
                    $whereArray[] = "$column $operator ?";
                    $bindings[] = $value;
                } else {
                    $whereArray[] = "$column = ?";
                    $bindings[] = $value;
                }
            }
            $where = 'WHERE ' . implode(' AND ', $whereArray);
        }
        $sql .= " $where";
        if (!empty($options)) {
            foreach ($options as $option => $value) {
                $sql .= " $option $value";
            }
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    /**
     * Updates rows in the specified table that match the specified criteria with the given data.
     *
     * @param string $table the name of the table
     * @param array $data an associative array of column names and new values
     * @param string $where the WHERE clause for the query (default: '')
     * @param array $bindings an array of values to bind to the placeholders in the WHERE clause (default: array())
     *
     * @return bool true if the update was successful, false otherwise
     */
    public function update($table, $data, $where = '', $bindings = array())
    {
        $set = array();
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }
        $set = implode(', ', $set);
        $sql = "UPDATE $table SET $set";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $values = array_values($data);
        $stmt = $this->pdo->prepare($sql);
        $values = array_merge($values, $bindings);
        return $stmt->execute($values);
    }

    /**
     * Delete rows from a table.
     *
     * @param string $table    The name of the table to delete rows from.
     * @param string $where    The WHERE clause for the delete statement.
     * @param array  $bindings An array of parameter values to bind to the SQL statement.
     *
     * @return bool Whether the delete statement was successful.
     */
    public function delete($table, $where = '', $bindings = array())
    {
        $sql = "DELETE FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }

    /**
     * Get the number of rows in a table.
     *
     * @param string $table    The name of the table to count rows in.
     * @param string $where    The WHERE clause for the count statement.
     * @param array  $bindings An array of parameter values to bind to the SQL statement.
     *
     * @return int The number of rows in the table.
     */
    public function count($table, $where = '', $bindings = array())
    {
        $sql = "SELECT COUNT(*) FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchColumn();
    }

    /**
     * Get the sum of a column in a table.
     *
     * @param string $table    The name of the table to sum the column in.
     * @param string $column   The name of the column to sum.
     * @param string $where    The WHERE clause for the sum statement.
     * @param array  $bindings An array of parameter values to bind to the SQL statement.
     *
     * @return int The sum of the column in the table.
     */
    public function sum($table, $column, $where = '', $bindings = array())
    {
        $sql = "SELECT SUM($column) FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchColumn();
    }

    /**
     * Calculates the average of a column in a table.
     *
     * @param string $table    The name of the table to select from.
     * @param string $column   The name of the column to calculate the average of.
     * @param string $where    Optional WHERE clause to filter results.
     * @param array  $bindings Optional parameter bindings for the WHERE clause.
     *
     * @return mixed The average of the column as a float, or FALSE on failure.
     */
    public function avg($table, $column, $where = '', $bindings = array())
    {
        $sql = "SELECT AVG($column) FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchColumn();
    }

    /**
     * Finds the minimum value of a column in a table.
     *
     * @param string $table    The name of the table to select from.
     * @param string $column   The name of the column to find the minimum of.
     * @param string $where    Optional WHERE clause to filter results.
     * @param array  $bindings Optional parameter bindings for the WHERE clause.
     *
     * @return mixed The minimum value of the column, or FALSE on failure.
     */
    public function min($table, $column, $where = '', $bindings = array())
    {
        $sql = "SELECT MIN($column) FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchColumn();
    }

    /**
     * Finds the maximum value of a column in a table.
     *
     * @param string $table    The name of the table to select from.
     * @param string $column   The name of the column to find the maximum of.
     * @param string $where    Optional WHERE clause to filter results.
     * @param array  $bindings Optional parameter bindings for the WHERE clause.
     *
     * @return mixed The maximum value of the column, or FALSE on failure.
     */
    public function max($table, $column, $where = '', $bindings = array())
    {
        $sql = "SELECT MAX($column) FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchColumn();
    }

    /**
     * Runs a SQL query and returns the results.
     *
     * @param string $sql      The SQL query to run.
     * @param array  $bindings Optional parameter bindings for the SQL query.
     *
     * @return array An array of results from the SQL query.
     */
    public function query($sql, $bindings = array())
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    /**
     * Retrieves a row from a table by ID.
     *
     * @param string $table The name of the table to select from.
     * @param int    $id    The ID of the row to select.
     *
     * @return array The row from the table with the specified ID.
     */
    public function get($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    /**
     * Retrieves a row from a table by ID using a custom SQL query.
     *
     * @param string $table   The name of the table to select from.
     * @param int    $id      The ID of the row to select.
     * @param string $columns The columns to select from the table.
     *
     * @return array The row from the table with the specified ID.
     *
     * @throws \Exception If the query file cannot be found.
     */
    public function find($table, $id, $columns = '*')
    {
        $sql_file = "SELECT_BY_ID_$table.sql";
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array($id));
            return $stmt->fetch();
        } else {
            throw new \Exception("Cannot find query file: $sql_file");
        }
    }

    /**
     * Retrieves multiple rows from a table.
     *
     * @param string $table    The name of the table to select from.
     * @param string $columns  The columns to select from the table.
     * @param string $where    The WHERE clause of the SQL query.
     * @param array  $bindings Optional parameter bindings for the SQL query.
     *
     * @return array An array of rows from the table that match the WHERE clause.
     */
    public function findAll($table, $columns = '*', $where = '', $bindings = array())
    {
        $sql = "SELECT $columns FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    /**
     * Saves data to a table, either by updating an existing row or creating a new one.
     *
     * @param string $table The name of the table to save to.
     * @param array  $data  An associative array of column names and values to save.
     *
     * @return bool True if the save was successful, false otherwise.
     */
    public function save($table, $data)
    {
        if (isset($data['id'])) {
            $set = array();
            foreach ($data as $column => $value) {
                if ($column !== 'id') {
                    $set[] = "$column = ?";
                }
            }
            $set = implode(', ', $set);
            $sql = "UPDATE $table SET $set WHERE id = ?";
            $values = array_values($data);
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(array_merge($values, array($data['id'])));
        } else {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $values = array_values($data);
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($values);
        }
    }

    /**
     * Sets the character set for the database connection.
     *
     * @param string $charset The character set to use.
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
        $this->pdo = new \PDO($dsn, $this->username, $this->password);
    }
}