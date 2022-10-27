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
            $row['order']
        );
    }

    private function playersFromRows($rows) {
        $players = [];

        foreach($rows as $row) {
            $players[] = new Player(
                $row['id'],
                $row['game_id'],
                $row['name'],
                $row['token'],
                $row['order']
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

    public function getByGameIdAndToken($gameId, $token) {
        $query = "SELECT * " . 
                 "FROM players " . 
                 "WHERE game_id = :game_id AND " . 
                 "token = :token";

        $rows = $this->db->query($query, [
            ':game_id' => $gameId,
            ':token' => $token
        ]);

        if(!$rows) {
            return null;
        }

        return $this->playerFromRow($rows[0]);
    }

    public function insert(Player $player) {
        $query = "INSERT INTO players " .
                 "(game_id, name, token, `order`) " . 
                 "VALUES " .
                 "(:game_id, :name, :token, :order)";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken(),
            ':order' => $player->getOrder()
        ]);

        return new Player(
            $this->db->lastInsertId(),
            $player->getGameId(),
            $player->getName(),
            $player->getToken(),
            $player->getOrder()
        );
    }

    public function update(Player $player) {
        $query = "UPDATE players " . 
                 "SET game_id = :game_id, " . 
                 "name = :name, " . 
                 "token = :token, " . 
                 "`order` = :order " .
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken(),
            ":order" => $player->getOrder(),
            ':id' => $player->getId()
        ]);
    }

    public function delete(Player $player) {
        $query = "DELETE FROM players WHERE id = :id";

        $this->db->query($query, [':id' => $player->getId()]);
    }
}