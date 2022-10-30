<?php
class DatabaseHelper {
    private $pdo = null;

    public function __construct($host, $dbname, $username, $password) {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username,
            $password);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query($query, $params = []) {
        $stmt = $this->pdo->prepare($query);
        
        $stmt->execute($params);

        if(preg_match('/^\s*SELECT/i', $query) === 1) {
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $rows;
        }

        return true;
    }

    public function selectAll($tableName, $params) {

    }

    public function insert($tableName, $params) {
        $query = "INSERT INTO $tableName (";

        $query .= implode(',', array_keys($params));

        $query .= ") VALUES (";

        $query .= implode(',', array_fill(0, count($params), '?'));
        
        $query .= ")";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute(array_values($params));

        return true;
    }

    public function update($tableName, $params, $conditions) {
        $query = "UPDATE $tableName SET ";

        $keys = array_map(function($key) {
            return "$key=?";
        }, array_keys($params));

        echo '<pre>'; print_r($keys); echo '</pre>';

        die();

        $stmt = $this->pdo->prepare($query);

        $stmt->execute(array_values($params));

        return true;
    }

    public function delete($tableName, $params) {

    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}