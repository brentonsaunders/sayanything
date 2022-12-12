<?php
namespace Repositories;

use Daos\AnswerDaoInterface;
use Models\Answer;

class AnswerRepository implements AnswerRepositoryInterface {
    private AnswerDaoInterface $answerDao;

    public function __construct(AnswerDaoInterface $answerDao) {
        $this->answerDao = $answerDao;
    }

    public function getAll() {
        return $this->answerDao->getAll();
    }

    public function getById($id) {
        return $this->answerDao->getById($id);
    }

    public function getByRoundId($roundId) {
        return $this->answerDao->getByRoundId($roundId);
    }

    public function insertAnswer(Answer $answer) : Answer {
        return $this->answerDao->insert($answer);
    }

    public function insertAnswers($answers) : array {
        $arr = [];

        foreach($answers as $answer) {
            $arr[] = $this->insertAnswer($answer);
        }

        return $arr;
    }

    public function updateAnswer(Answer $answer) : Answer {
        if(!$this->getById($answer->getId())) {
            return $this->insertAnswer($answer);
        }

        return $this->answerDao->update($answer);
    }

    public function updateAnswers($answers) : array {
        $arr = [];

        foreach($answers as $answer) {
            $arr[] = $this->updateAnswer($answer);
        }

        return $arr;
    }

    public function deleteAnswer(Answer $answer) : Answer {
        return $this->answerDao->delete($answer);
    }

    public function deleteAnswers($answers) : array {
        $arr = [];

        foreach($answers as $answer) {
            $arr[] = $this->deleteAnswer($answer);
        }

        return $arr;
    }
}