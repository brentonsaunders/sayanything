<?php
namespace Daos;

use DatabaseHelper;
use Models\Question;

class QuestionDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function getById($id) {
        $query = "SELECT * " . 
                 "FROM questions " . 
                 "WHERE id = :id";

        $rows = $this->db->query($query, [
            ':id' => $id
        ]);

        if(!$rows) {
            return null;
        }

        $row = $rows[0];

        return new Question(
            $row['id'],
            $row['question']
        );
    }

    public function insert(Question $question) {
        $query = "INSERT INTO questions (card_id, question) " . 
                 "VALUES (:question)";

        $this->db->query($query, [
            ':card_id' => $question->getCardId(),
            ':question' => $question->getQuestion()
        ]);

        return $this->getById($this->db->lastInsertId());
    }

    public function update(Question $question) {
        $query = "UPDATE questions " . 
                 "SET card_id = :card_id, "
                 "question = :question " . 
                 "WHERE id = :id";

        $this->db->query($query, [
            ':card_id' => $question->getCardId(),
            ':question' => $question->getQuestion(),
            ':id' => $question->getId()
        ]);
    }

    public function delete(Question $question) {
        $query = "DELETE FROM questions WHERE id = :id";

        $this->db->query($query, [':id' => $question->getId()]);
    }
}