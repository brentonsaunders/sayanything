<?php
namespace Daos;

use DatabaseHelper;
use Models\Answer;

class AnswerDao implements AnswerDaoInterface {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function answerFromRow($row) {
        return new Answer(
            $row['id'],
            $row['player_id'],
            $row['round_id'],
            $row['answer']
        );
    }

    private function answersFromRows($rows) {
        $answers = [];

        foreach($rows as $row) {
            $answers[] = $this->answerFromRow($row);
        }

        return $answers;
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

        return $this->answerFromRow($rows[0]);
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

        return $this->answersFromRows($rows);
    }

    public function insert(Answer $answer) : Answer {
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

    public function update(Answer $answer) : Answer {
        $query = "UPDATE answers " . 
                 "SET player_id = :player_id, " . 
                 "round_id = :round_id, " . 
                 "answer = :answer " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':player_id' => $answer->getPlayerId(),
            ":round_id" => $answer->getRoundId(),
            ":answer" => $answer->getAnswer(),
            ':id' => $answer->getId()
        ]);

        return $answer;
    }

    public function delete(Answer $answer) : Answer {
        $query = "DELETE FROM answers WHERE id = :id";

        $this->db->query($query, [
            ':id' => $answer->getId()
        ]);

        return $answer;
    }
}