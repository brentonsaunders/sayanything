<?php
namespace Daos;

use DatabaseHelper;
use Models\Player;

class PlayerDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function playerFromRow($row) {
        return new Player(
            $row['id'],
            $row['game_id'],
            $row['name'],
            $row['token']
        );
    }

    private function playersFromRows($rows) {
        $players = [];

        foreach($rows as $row) {
            $players[] = new Player(
                $row['id'],
                $row['game_id'],
                $row['name'],
                $row['token']
            );
        }

        return $players;
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM players " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        return $this->playerFromRow($rows[0]);
    }

    public function getByGameId($gameId) {
        $query = "SELECT * " . 
                 "FROM players " . 
                 "WHERE game_id = :game_id";

        $rows = $this->db->query($query, [
            ':game_id' => $gameId
        ]);

        if(!$rows) {
            return null;
        }

        return $this->playersFromRows($rows);
    }

    public function insert(Player $player) {
        $query = "INSERT INTO players (game_id, name, token) " . 
                 "VALUES (:game_id, :name, :token)";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken()
        ]);

        return new Player(
            $this->db->lastInsertId(),
            $player->getGameId(),
            $player->getName(),
            $player->getToken()
        );
    }

    public function update(Player $player) {
        $query = "UPDATE players " . 
                 "SET game_id = :game_id, " . 
                 "name = :name, " . 
                 "token = :token " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken(),
            ':id' => $player->getId()
        ]);
    }

    public function delete(Player $player) {
        $query = "DELETE FROM players WHERE id = :id";

        $this->db->query($query, [':id' => $player->getId()]);
    }
}