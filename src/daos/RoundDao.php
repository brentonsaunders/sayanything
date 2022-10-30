<?php
namespace Daos;

use DatabaseHelper;
use Models\Round;

class RoundDao implements RoundDaoInterface {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function roundFromRow($row) {
        return new Round(
            $row['id'],
            $row['game_id'],
            $row['judge_id'],
            $row['card_id'],
            $row['question_id'],
            $row['chosen_answer_id']
        );
    }

    private function roundsFromRows($rows) {
        $rounds = [];

        foreach($rows as $row) {
            $rounds[] = $this->roundFromRow($row);
        }

        return $rounds;
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

        return $this->roundFromRow($rows[0]);
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

        return $this->roundsFromRows($rows);
    }

    public function insert(Round $round) : Round {
        $query = "INSERT INTO rounds " . 
                 "(game_id, judge_id, card_id, question_id, chosen_answer_id) " .
                 "VALUES " . 
                 "(:game_id, :judge_id, :card_id, :question_id, :chosen_answer_id)";

        $this->db->query($query, [
            ':game_id' => $round->getGameId(),
            ':judge_id' => $round->getJudgeId(),
            ':card_id' => $round->getCardId(),
            ':question_id' => $round->getQuestionId(),
            ":chosen_answer_id" => $round->getChosenAnswerId()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Round $round) : Round {
        $query = "UPDATE rounds " . 
                 "SET game_id = :game_id, " . 
                 "judge_id = :judge_id, " . 
                 "card_id = :card_id, " . 
                 "question_id = :question_id, " .
                 "chosen_answer_id = :chosen_answer_id " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':game_id' => $round->getGameId(),
            ':judge_id' => $round->getJudgeId(),
            ':card_id' => $round->getCardId(),
            ':question_id' => $round->getQuestionId(),
            ":chosen_answer_id" => $round->getChosenAnswerId(),
            ':id' => $round->getId()
        ]);

        return $round;
    }

    public function delete(Round $round) : Round {
        $query = "DELETE FROM rounds WHERE id = :id";

        $this->db->query($query, [
            ':id' => $round->getId()
        ]);

        return $round;
    }
}