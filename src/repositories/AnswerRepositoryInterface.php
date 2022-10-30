<?php
namespace Repositories;

use Models\Answer;

interface AnswerRepositoryInterface {
    public function getById($id);
    public function getByRoundId($roundId);
    public function insertAnswer(Answer $answer) : Answer;
    public function insertAnswers($answers) : array;
    public function updateAnswer(Answer $answer) : Answer;
    public function updateAnswers($answers) : array;
    public function deleteAnswer(Answer $answer) : Answer;
    public function deleteAnswers($answers) : array;
}