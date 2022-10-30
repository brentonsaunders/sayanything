<?php
namespace Daos;

use DatabaseHelper;
use Models\Vote;

class VoteDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM votes " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        $row = $rows[0];

        return new Vote(
            $row['id'],
            $row['player_id'],
            $row['answer_id']
        );
    }

    public function getByPlayerIdAndRoundId($playerId, $roundId) {
        $query = "SELECT * " . 
                 "FROM votes " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        $votes = [];

        for($rows as $row) {
            $votes[] = new Vote(
                $row['id'],
                $row['player_id'],
                $row['answer_id']
            );
        }

        return $votes;
    }

    public function insert(Vote $vote) {
        $query = "INSERT INTO votes " . 
                 "(player_id, answer_id) " . 
                 "VALUES " . 
                 "(:player_id, :answer_id)";

        $this->db->query($query, [
            ':player_id' => $vote->getPlayerId(),
            ':answer_id' => $vote->getAnswerId()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Vote $vote) {
        $query = "UPDATE votes " . 
                 "SET player_id = :player_id, " . 
                 "answer_id = :answer_id " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':player_id' => $vote->getPlayerId(),
            ':answer_id' => $vote->getAnswerId(),
            ':id' => $vote->getId()
        ]);
    }

    public function delete(Vote $vote) {
        $query = "DELETE FROM votes WHERE id = :id";

        $this->db->query($query, [
            ':id' => $id
        ]);
    }
}