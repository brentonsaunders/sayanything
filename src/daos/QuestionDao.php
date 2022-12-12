<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Question;

class QuestionDao implements QuestionDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id): ?Question {
        $results = $this->mapper->select("Models\\Question", ["id" => $id]);

        return count($results) === 1 ? $results[0] : null;
    }

    public function getByCardId($cardId): array {
        return $this->mapper->select("Models\\Question", ["card_id" => $cardId]);
    }

    public function insert(Question $question) : Question {
        return $this->mapper->insert($question);
    }

    public function update(Question $question) : Question {
        return $this->mapper->update($question);
    }

    public function delete(Question $question) : Question {
        return $this->mapper->delete($question);
    }
}