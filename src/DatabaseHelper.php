<?php
class DatabaseHelper {
    private $pdo = null;

    public function __construct($host, $dbname, $username, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username,
                $password);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die('Error connecting to the database: ' . $e->getMessage());
        }
    }

    public function query($query, $params = []) {
        try {
            $stmt = $this->pdo->prepare($query);
            
            $stmt->execute($params);

            if(preg_match('/^\s*SELECT/i', $query) === 1) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $rows;
            }

            return true;
        } catch(PDOException $e) {
            echo "Error querying database: " . $e->getMessage() . "<br>";
            
            echo "<pre>";

            debug_print_backtrace();

            echo "</pre>";

            die();
        }

        return false;
    }

    public function selectAll($tableName, $params) {

    }

    public function insert($tableName, $params) {
        try {
            $query = "INSERT INTO $tableName (";

            $query .= implode(',', array_keys($params));

            $query .= ") VALUES (";

            $query .= implode(',', array_fill(0, count($params), '?'));
            
            $query .= ")";

            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array_values($params));

            return true;
        } catch(PDOException $e) {
            echo "Error querying database: " . $e->getMessage() . "<br>";
            
            echo "<pre>";

            debug_print_backtrace();

            echo "</pre>";

            die();
        }

        return false;
    }

    public function update($tableName, $params, $conditions) {
        try {
            $query = "UPDATE $tableName SET ";

            $keys = array_map(function($key) {
                return "$key=?";
            }, array_keys($params));

            echo '<pre>'; print_r($keys); echo '</pre>';

            die();

            $stmt = $this->pdo->prepare($query);

            $stmt->execute(array_values($params));

            return true;
        } catch(PDOException $e) {
            echo "Error querying database: " . $e->getMessage() . "<br>";
            
            echo "<pre>";

            debug_print_backtrace();

            echo "</pre>";

            die();
        }
    }

    public function delete($tableName, $params) {

    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}