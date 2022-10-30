<?php
namespace Daos;

use DatabaseHelper;

class TokensDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function get() {
        $rows = $this->db->query("SELECT * FROM tokens");

        if(!$rows) {
            return null;
        }

        $tokens = array_map(function($row) { return $row['name']; }, $rows);

        return $tokens;
    }
}