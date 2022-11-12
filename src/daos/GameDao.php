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
                 "(id, name, creator_id, state, time_updated, " . 
                 "time_created) " . 
                 "VALUES " . 
                 "(:id, :name, :creator_id, :state, NOW(), " . 
                 "NOW())";

        $this->db->query($query, [
            ':id' => $game->getId(),
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ':state' => $game->getState()
        ]);

        return $this->getById($game->getId());
    }

    public function update(Game $game) : Game {
        $params = [
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ':state' => $game->getState(),
            ':time_created' => $game->getTimeCreated(),
            ':id' => $game->getId()
        ];

        $timeUpdated = ":time_updated";

        if($game->stateHasChanged()) {
            $timeUpdated = "NOW()";
        }

        $query = "UPDATE games " .
                 "SET name = :name, " . 
                 "creator_id = :creator_id, " . 
                 "state = :state, " . 
                 "time_updated = $timeUpdated, " .
                 "time_created = :time_created " . 
                 "WHERE id = :id";

        if(!$game->stateHasChanged()) {
            $params[':time_updated'] = $game->getTimeUpdated();
        }

        $this->db->query($query, $params);

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