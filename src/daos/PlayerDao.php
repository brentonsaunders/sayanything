<?php
namespace Daos;

use DatabaseHelper;
use Models\Player;

class PlayerDao implements PlayerDaoInterface {
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
            $row['skip_turn'],
            $row['must_wait_for_next_round']
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

    public function insert(Player $player) : Player {
        $this->db->insert(
            'players',
            [
                'game_id' => $player->getGameId(),
                'name' => $player->getName(),
                'token' => $player->getToken(),
                'turn' => $player->getTurn(),
                'skip_turn' => ($player->getSkipTurn() ? 1 : 0),
                'must_wait_for_next_round' => ($player->getMustWaitForNextRound() ? 1 : 0),
            ]
        );

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Player $player) : Player {
        $query = "UPDATE players " . 
                 "SET game_id = :game_id, " . 
                 "name = :name, " . 
                 "token = :token, " . 
                 "turn = :turn, " .
                 "skip_turn = :skip_turn, " .
                 "must_wait_for_next_round = :must_wait_for_next_round " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $player->getGameId(),
            ':name' => $player->getName(),
            ':token' => $player->getToken(),
            ":turn" => $player->getTurn(),
            ':skip_turn' => ($player->getSkipTurn() ? 1 : 0),
            ':must_wait_for_next_round' => ($player->getMustWaitForNextRound() ? 1 : 0),
            ':id' => $player->getId()
        ]);

        return $player;
    }

    public function delete(Player $player) : Player {
        $query = "DELETE FROM players WHERE id = :id";

        $this->db->query($query, [':id' => $player->getId()]);

        return $player;
    }
}