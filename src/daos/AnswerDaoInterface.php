<?php
namespace Daos;

use Models\Answer;

interface AnswerDaoInterface {
    public function getById($id): ?Answer;
    public function getByRoundId($roundId): array;
    public function insert(Answer $answer) : Answer;
    public function update(Answer $answer) : Answer;
    public function delete(Answer $answer) : Answer;
}