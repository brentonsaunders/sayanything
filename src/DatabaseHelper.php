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
            die('Error querying database: ' . $e->getMessage());
        }

        return false;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}