<?php
namespace Database;

use Models\Game;

class MockDbSession implements DbSessionInterface {
    public function __construct() {
        
    }
	
	public function select($table, $columns = []) {
		if($table === "games" && isset($columns["id"]) && $columns["id"] == 1) {
			return [
				"id" => "1",
				"name" => "Test Game",
				"creator_id" => "1",
				"state" => Game::WAITING_FOR_PLAYERS,
				"current_round_id" => "1",
				"time_updated" => null,
				"time_created" => date("Y-m-d H:i:s")
			];
		}

		return []; 
	}
	
	
	public function insert($table, $columns) {
	}
	
	
	public function update($table, $columns, $primaryKey) {
	}
	
	
	public function delete($table, $primaryKey, $primaryKeyValue) {
	}
}