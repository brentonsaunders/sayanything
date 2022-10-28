<?php
namespace Daos;

use DatabaseHelper;
use Models\Round;

class RoundDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM rounds " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        $row = $rows[0];

        return new Round(
            $row['id'],
            $row['game_id'],
            $row['active_player_id'],
            $row['question_id'],
            $row['chosen_answer_id']
        );
    }

    public function getByGameId($gameId) {
        $query = "SELECT * " . 
                 "FROM rounds " . 
                 "WHERE game_id = :game_id";

        $rows = $this->db->query($query, [
            ':game_id' => $gameId
        ]);

        if(!$rows) {
            return null;
        }

        $rounds = [];

        foreach($rows as $row) {
            $rounds[] = new Round(
                $row['id'],
                $row['game_id'],
                $row['active_player_id'],
                $row['question_id'],
                $row['chosen_answer_id']
            );
        }

        return $rounds;
    }

    public function insert(Round $round) {
        $query = "INSERT INTO rounds " . 
                 "(game_id, active_player_id, question_id, chosen_answer_id) " .
                 "VALUES " . 
                 "(:game_id, :active_player_id, :question_id, :chosen_answer_id)";

        $this->db->query($query, [
            ':game_id' => $round->getGameId(),
            ':active_player_id' => $round->getActivePlayerId(),
            ':question_id' => $round->getQuestionId(),
            ":chosen_answer_id" => $round->getChosenAnswerId()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Round $round) {
        $query = "UPDATE rounds " . 
                 "SET game_id = :game_id, " . 
                 "active_player_id = :active_player_id, " . 
                 "question_id = :question_id, " . 
                 "chosen_answer_id = :chosen_answer_id " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $round->getGameId(),
            ':active_player_id' => $round->getActivePlayerId(),
            ':question_id' => $round->getQuestionId(),
            ":chosen_answer_id" => $round->getChosenAnswerId(),
            ':id' => $round->getId()
        ]);
    }

    public function delete(Round $round) {
        $query = "DELETE FROM rounds WHERE id = :id";

        $this->db->query($query, [
            ':id' => $round->getId()
        ]);
    }
}