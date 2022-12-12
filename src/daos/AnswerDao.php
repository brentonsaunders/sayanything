<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Answer;

class AnswerDao implements AnswerDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id): ?Answer {
        $results = $this->mapper->select("Models\\Answer", ["id" => $id]);

        return count($results) === 1 ? $results[0] : null;
    }

    public function getByRoundId($roundId): array {
        return $this->mapper->select("Models\\Answer", ["round_id" => $roundId]);
    }

    public function insert(Answer $answer) : Answer {
        return $this->mapper->insert($answer);
    }

    public function update(Answer $answer) : Answer {
        return $this->mapper->update($answer);
    }

    public function delete(Answer $answer) : Answer {
        return $this->mapper->delete($answer);
    }
}
