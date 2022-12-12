<?php
namespace Database;

use PDO;

class PdoSession implements DbSessionInterface {
    private PDO $pdo;

    public function __construct($host, $dbname, $username, $password) {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function select($table, $columns = []) {
        $query = "SELECT * FROM $table ";

        if (count($columns) > 0) {
            $query .= "WHERE ";
            
            $query .= implode(" AND ", array_map(function ($key) {
                return "$key=?";
            }, array_keys($columns)));
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->execute(array_values($columns));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $columns) {
        $query = "INSERT INTO $table (";

        $query .= implode(",", array_keys($columns));

        $query .= ") VALUES (";

        $query .= implode(",", array_map(function ($key) {
            return ":$key";
        }, array_keys($columns)));

        $query .= ")";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($columns);
    }

    public function update($table, $columns, $primaryKey) {
        $query = "UPDATE $table SET ";

        $query .= implode(",", array_map(function ($key) {
            return "$key=:$key";
        }, array_diff(array_keys($columns), [$primaryKey])));

        $query .= " WHERE $primaryKey=:$primaryKey";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($columns);
    }

    public function delete($table, $primaryKey, $primaryKeyValue) {
        $query = "DELETE FROM $table WHERE $primaryKey=?";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$primaryKeyValue]);
    }
}
