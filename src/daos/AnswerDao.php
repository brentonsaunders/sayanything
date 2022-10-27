<?php
namespace Daos;

use DatabaseHelper;
use Models\Answer;

class AnswerDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM answers " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        $row = $rows[0];

        return new Answer(
            $row['id'],
            $row['player_id'],
            $row['round_id'],
            $row['question']
        );
    }

    public function getByRoundId($roundId) {
        $query = "SELECT * " . 
                 "FROM answers " . 
                 "WHERE round_id = :round_id";

        $rows = $this->db->query($query, [
            ':round_id' => $roundId
        ]);

        if(!$rows) {
            return null;
        }

        $rounds = [];

        for($rows as $row) {
            $rounds[] = new Answer(
                $row['id'],
                $row['player_id'],
                $row['round_id'],
                $row['question']
            );
        }

        return $rounds;
    }

    public function insert(Answer $answer) {
        $query = "INSERT INTO answers " . 
                 "(player_id, round_id, answer) " . 
                 "VALUES " . 
                 "(:player_id, :round_id, :answer)";

        $this->db->query($query, [
            ':player_id' => $answer->getPlayerId(),
            ':round_id' => $answer->getRoundId(),
            ':answer' => $answer->getAnswer()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Answer $answer) {
        $query = "UPDATE answers " . 
                 "SET player_id = :player_id, " . 
                 "round_id = :round_id, " . 
                 "answer = :answer " . 
                 "SET id = :id";

        $this->db->query($query, [
            ':player_id' => $answer->getPlayerId(),
            ":round_id" => $answer->getRoundId(),
            ":answer" => $answer->getAnswer(),
            ':id' => $answer->getId()
        ]);
    }

    public function delete(Answer $answer) {
        $query = "DELETE FROM answers WHERE id = :id";

        $this->db->query($query, [
            ':id' => $answer->getId()
        ]);
    }
}