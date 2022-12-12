<?php
namespace Database;

use Exception;

class DbMapper implements DbMapperInterface {
    private DbSessionInterface $db;
    private $mappings = [];

    public function __construct(DbSessionInterface $db) {
        $this->db = $db;
    }
    public function map($class, $table, $primaryKey, $columnFields) {
        if(!class_exists($class)) {
            throw new DbMapperException("Class $class doesn't exist.");
        }

        if(empty($table)) {
            throw new DbMapperException("Missing table name.");
        }

        if(empty($primaryKey)) {
            throw new DbMapperException("Primary key is missing.");
        }

        if(!is_array($columnFields) || empty($columnFields)) {
            throw new DbMapperException("Missing an array mapping columns to fields.");
        }

        foreach($columnFields as $column => $field) {
            if(!is_string($column)) {
                throw new DbMapperException("Column must be a string in mapping.");
            }

            if(empty($field)) {
                throw new DbMapperException("Missing field for $column in mapping.");
            }

            $getter = "get" . $field;
            $setter = "set" . $field;

            if(!method_exists($class, $getter)) {
                throw new DbMapperException("Missing getter for $field in $class.");
            }

            if(!method_exists($class, $setter)) {
                throw new DbMapperException("Missing setter for $field in $class.");
            }
        }

        $this->mappings[$class] = [
            "table" => $table,
            "primaryKey" => $primaryKey,
            "columnFields" => $columnFields
        ];
    }
    public function select($class, $matchingColumns = []) {
        if(!array_key_exists($class, $this->mappings)) {
            throw new DbMapperException("No mapping exists for $class.");
        }

        $mapping = $this->mappings[$class];

        $table = $mapping["table"];
        $primaryKey = $mapping["primaryKey"];
        $columnFields = $mapping["columnFields"];

        $rows = $this->db->select($table, $matchingColumns);

        $objects = [];

        foreach($rows as $row) {
            $object = new $class();

            foreach ($row as $column => $value) {
                if(!array_key_exists($column, $columnFields)) {
                    continue;
                }

                $this->set($object, $column, $value, $columnFields);
            }

            $objects[] = $object;
        }

        return $objects;
    }
    public function insert($object) {
        $class = get_class($object);

        if(!array_key_exists($class, $this->mappings)) {
            throw new DbMapperException("No mapping exists for $class.");
        }

        $mapping = $this->mappings[$class];

        $table = $mapping["table"];
        $primaryKey = $mapping["primaryKey"];
        $columnFields = $mapping["columnFields"];

        $params = [];

        foreach($columnFields as $column => $field) {
            $params[$column] = $this->get($object, $column, $columnFields);
        }

        $this->db->insert($table, $params);

        return $object;
    }
    public function update($object) {
        $class = get_class($object);

        if(!array_key_exists($class, $this->mappings)) {
            throw new DbMapperException("No mapping exists for $class.");
        }

        $mapping = $this->mappings[$class];

        $table = $mapping["table"];
        $primaryKey = $mapping["primaryKey"];
        $columnFields = $mapping["columnFields"];

        $params = [];

        foreach($columnFields as $column => $field) {
            $params[$column] = $this->get($object, $column, $columnFields);
        }

        $this->db->update($table, $params, $primaryKey);

        return $object;
    }
    public function delete($object) {
        $class = get_class($object);

        if(!array_key_exists($class, $this->mappings)) {
            throw new DbMapperException("No mapping exists for $class.");
        }

        $mapping = $this->mappings[$class];

        $table = $mapping["table"];
        $primaryKey = $mapping["primaryKey"];
        $columnFields = $mapping["columnFields"];

        $primaryKeyValue = $this->get($object, $primaryKey, $columnFields);

        $this->db->delete($table, $primaryKey, $primaryKeyValue);

        return $object;
    }

    private function set($object, $column, $value, $columnFields) {
        $setter = "set" . $columnFields[$column];

        return call_user_func_array([$object, $setter], [$value]);
    }

    private function get($object, $column, $columnFields) {
        $getter = "get" . $columnFields[$column];

        return call_user_func([$object, $getter]);
    }
}

class DbMapperException extends Exception {}
