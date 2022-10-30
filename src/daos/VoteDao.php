<?php
namespace Daos;

use DatabaseHelper;
use Models\Vote;

class VoteDao implements VoteDaoInterface {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function voteFromRow($row) {
        return new Vote(
            $row['id'],
            $row['round_id'],
            $row['player_id'],
            $row['answer_id']
        );
    }

    private function votesFromRows($rows) {
        $votes = [];

        foreach($rows as $row) {
            $votes[] = $this->voteFromRow($row);
        }

        return $votes;
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

        return $this->voteFromRow($rows[0]);
    }

    public function getByRoundId($roundId) {
        $query = "SELECT * " . 
                 "FROM votes " . 
                 "WHERE round_id = :round_id";

        $rows = $this->db->query($query, [
            ':round_id' => $roundId
        ]);

        if(!$rows) {
            return null;
        }

        return $this->votesFromRows($rows);
    }

    public function insert(Vote $vote) : Vote {
        $query = "INSERT INTO votes " . 
                 "(round_id, player_id, answer_id) " . 
                 "VALUES " . 
                 "(:round_id, :player_id, :answer_id)";

        $this->db->query($query, [
            ':round_id' => $vote->getRoundId(),
            ':player_id' => $vote->getPlayerId(),
            ':answer_id' => $vote->getAnswerId()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Vote $vote) : Vote {
        $query = "UPDATE votes " . 
                 "SET round_id = :round_id, " .
                 "player_id = :player_id, " . 
                 "answer_id = :answer_id " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ":round_id" => $vote->getRoundId(),
            ':player_id' => $vote->getPlayerId(),
            ':answer_id' => $vote->getAnswerId(),
            ':id' => $vote->getId()
        ]);

        return $vote;
    }

    public function delete(Vote $vote) : Vote {
        $query = "DELETE FROM votes WHERE id = :id";

        $this->db->query($query, [
            ':id' => $id
        ]);

        return $vote;
    }
}