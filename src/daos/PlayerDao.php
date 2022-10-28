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
            $row['token'],
            $row['turn'],
            $row['skip_turn']
        );
    }

    private function playersFromRows($rows) {
        $players = [];

        foreach($rows as $row) {
            $players[] = $this->playerFromRow($row);
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

    public function getByGameIdAndTurn($gameId, $turn) {
        $query = "SELECT * " . 
                 "FROM players " . 
                 "WHERE game_id = :game_id AND " . 
                 "turn = :turn";

        $rows = $this->db->query($query, [
            ':game_id' => $gameId,
            ':turn' => $turn
        ]);

        if(!$rows) {
            return null;
        }

        return $this->playerFromRow($rows[0]);
    }

    public function insert(Player $player) {
        $this->db->insert(
            'players',
            [
                'game_id' => $player->getGameId(),
                'name' => $player->getName(),
                'token' => $player->getToken(),
                'turn' => $player->getTurn(),
                'skip_turn' => ($player->getSkipTurn() ? 1 : 0)
            ]
        );

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Player $player) {
        $query = "UPDATE players " . 
                 "SET game_id = :game_id, " . 
                 "name = :name, " . 
                 "token = :token, " . 
                 "turn = :turn, " .
                 "skip_turn = :skip_turn " .
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken(),
            ":turn" => $player->getTurn(),
            ':skip_turn' => ($player->getSkipTurn() ? 1 : 0),
            ':id' => $player->getId()
        ]);
    }

    public function delete(Player $player) {
        $query = "DELETE FROM players WHERE id = :id";

        $this->db->query($query, [':id' => $player->getId()]);
    }
}