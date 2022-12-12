<?php
namespace Database;

interface DbMapperInterface {
    public function select($object, $matchingColumns = []);
    public function insert($object);
    public function update($object);
    public function delete($object);
}