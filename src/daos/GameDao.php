<?php
namespace Daos;

use DatabaseHelper;
use Models\Game;

class GameDao implements GameDaoInterface {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function gameFromRow($row) {
        return new Game(
            $row['id'],
            $row['name'],
            $row['creator_id'],
            $row['current_round_id'],
            $row['state'],
            $row['time_updated'],
            $row['time_created']
        );
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM games " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        return $this->gameFromRow($rows[0]);
    }

    public function insert(Game $game) : Game {
        $query = "INSERT INTO games " .
                 "(id, name, creator_id, current_round_id, state, time_updated, " . 
                 "time_created) " . 
                 "VALUES " . 
                 "(:id, :name, :creator_id, :current_round_id, :state, NOW(), " . 
                 "NOW())";

        $this->db->query($query, [
            ':id' => $game->getId(),
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ":current_round_id" => $game->getCurrentRoundId(),
            ':state' => $game->getState()
        ]);

        return $this->getById($game->getId());
    }

    public function update(Game $game) : Game {
        $query = "UPDATE games " .
                 "SET name = :name, " . 
                 "creator_id = :creator_id, " . 
                 "current_round_id = :current_round_id, " . 
                 "state = :state, " . 
                 'time_updated = NOW(), ' .
                 "time_created = :time_created " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ":current_round_id" => $game->getCurrentRoundId(),
            ':state' => $game->getState(),
            ':time_created' => $game->getTimeCreated(),
            ':id' => $game->getId()
        ]);

        return $game;
    }

    public function delete(Game $game) : Game {
        $query = "DELETE FROM games WHERE id = :id";

        $this->db->query($query, [
            ':id' => $game->getId()
        ]);

        return $game;
    }
}