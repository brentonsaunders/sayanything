<?php
namespace Daos;

use Models\Answer;

interface AnswerDaoInterface {
    public function getById($id);
    public function getByRoundId($roundId);
    public function insert(Answer $answer) : Answer;
    public function update(Answer $answer) : Answer;
    public function delete(Answer $answer) : Answer;
}