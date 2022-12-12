<?php
namespace Daos;

use DatabaseHelper;
use Models\Question;

class QuestionDao implements QuestionDaoInterface {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    private function questionFromRow($row) {
        return new Question(
            $row["id"],
            $row["card_id"],
             $row["question"]
        );
    }

    private function questionsFromRows($rows) {
        $questions = [];

        foreach($rows as $row) {
            $questions[] = $this->questionFromRow($row);
        }

        return $questions;
    }

    public function getById($id): ?Question {
        $query = "SELECT * " . 
                 "FROM questions " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        return $this->questionFromRow($rows[0]);
    }

    public function getByCardId($cardId): array {
        $query = "SELECT * " . 
                 "FROM questions " . 
                 "WHERE card_id = :card_id";

        $rows = $this->db->query($query, [
            ':card_id' => $cardId
        ]);

        if(!$rows) {
            return null;
        }

        return $this->questionsFromRows($rows);
    }

    public function insert(Question $question) : Question {
        $query = "INSERT INTO questions (card_id, question) " . 
                 "VALUES (:card_id, :question)";

        $this->db->query($query, [
            ':card_id' => $question->getCardId(),
            ':question' => $question->getQuestion()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Question $question) : Question {
        $query = "UPDATE questions " . 
                 "SET card_id = :card_id, " .
                 "question = :question " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':card_id' => $question->getCardId(),
            ':question' => $question->getQuestion(),
            ':id' => $question->getId()
        ]);

        return $question;
    }

    public function delete(Question $question) : Question {
        $query = "DELETE FROM questions WHERE id = :id";

        $this->db->query($query, [':id' => $question->getId()]);

        return $question;
    }
}