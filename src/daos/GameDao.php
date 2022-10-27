<?php
namespace Daos;

use DatabaseHelper;
use Models\Game;

class GameDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function gameFromRow($row) {
        return new Game(
            $row['id'],
            $row['friendly_id'],
            $row['name'],
            $row['creator_id'],
            $row['round_id'],
            $row['state'],
            $row['update_time'],
            $row['date_created']
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

    public function getByFriendlyId($friendlyId) {
        $query = "SELECT * " . 
                 "FROM games " . 
                 "WHERE friendly_id = :friendly_id";

        $rows = $this->db->query($query, [
            ':friendly_id' => $friendlyId
        ]);

        if(!$rows) {
            return null;
        }

        return $this->gameFromRow($rows[0]);
    }

    public function insert(Game $game) {
        $query = "INSERT INTO games " .
                 "(friendly_id, name, creator_id, round_id, state, update_time, date_created) " . 
                 "VALUES " . 
                 "(:friendly_id, :name, :creator_id, :round_id, :state, NOW(), NOW())";

        $this->db->query($query, [
            ':friendly_id' => $game->getFriendlyId(),
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ":round_id" => $game->getRoundId(),
            ':state' => $game->getState()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Game $game) {
        $query = "UPDATE games " . 
                 "SET friendly_id = :friendly_id, " . 
                 "name = :name, " . 
                 "creator_id = :creator_id, " . 
                 "round_id = :round_id, " . 
                 "state = :state, " . 
                 'update_time = NOW(), '
                 "date_created = :date_created " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':friendly_id' => $game->getFriendlyId(),
            ':name' => $game->getName(),
            ':creator_id' => $game->getCreatorId(),
            ":round_id" => $game->getRoundId(),
            ':state' => $game->getState(),
            ':date_created' => $game->getDateCreated(),
            ':id' => $game->getId()
        ]);
    }

    public function delete(Game $game) {
        $query = "DELETE FROM games WHERE id = :id";

        $this->db->query($query, [
            ':id' => $game->getId()
        ]);
    }
}