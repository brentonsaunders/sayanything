<?php
namespace Repositories;

use Daos\RoundDaoInterface;
use Models\Answer;
use Models\Round;
use Repositories\AnswerRepositoryInterface;

class RoundRepository implements RoundRepositoryInterface {
    private RoundDaoInterface $roundDao;
    private AnswerRepositoryInterface $answerRepository;

    public function __construct(RoundDaoInterface $roundDao,
    AnswerRepositoryInterface $answerRepository) {
        $this->roundDao = $roundDao;
        $this->answerRepository = $answerRepository;
    }

    private function get($round) {
        $answers = $this->answerRepository->getByRoundId($round->getId());

        $roundId = $round->getId();

        $round->setAnswers($answers);

        return $round;
    }

    public function getById($id) {
        $round = $this->roundDao->getById($id);

        if(!$round) {
            return null;
        }

        return $this->get($round);
    }

    public function getByGameId($gameId) {
        $rounds = $this->roundDao->getByGameId($gameId);

        if(!$rounds) {
            return null;
        }

        $arr = [];

        foreach($rounds as $round) {
            $arr[] = $this->get($round);
        }

        return $arr;
    }

    public function insertRound(Round $round) : Round {
        $round = $this->roundDao->insert($round);

        $answers = $round->getAnswers();

        if($answers) {
            $round->setAnswers($this->answerRepository->insertAnswers($answers));
        }

        return $round;
    }

    public function insertRounds($rounds) : array {
        $arr = [];

        foreach($rounds as $round) {
            $arr[] = $this->insertRound($round);
        }

        return $arr;
    }

    public function updateRound(Round $round) : Round {
        if(!$this->getById($round->getId())) {
            return $this->insertRound($round);
        }

        $round = $this->roundDao->update($round);

        $answers = $round->getAnswers();

        if($answers) {
            $round->setAnswers($this->answerRepository->updateAnswers($answers));
        }

        return $round;
    }

    public function updateRounds($rounds) : array {
        $arr = [];

        foreach($rounds as $round) {
            $arr[] = $this->updateRound($round);
        }

        return $arr;
    }

    public function deleteRound(Round $round) : Round {
        $answers = $round->getAnswers();

        if($answers) {
            $round->setAnswers($this->answerRepository->deleteAnswers($answers));
        }

        $round = $this->roundDao->delete($round);

        return $round;
    }

    public function deleteRounds($rounds) : array {
        $arr = [];

        foreach($rounds as $round) {
            $arr[] = $this->deleteRound($round);
        }

        return $arr;
    }
}