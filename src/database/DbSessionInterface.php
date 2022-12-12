<?php
namespace Database;

interface DbSessionInterface {
    public function select($table, $columns = []);
    public function insert($table, $columns);
    public function update($table, $columns, $primaryKey);
    public function delete($table, $primaryKey, $primaryKeyValue);
}