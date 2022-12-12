<?php
namespace Repositories;

use Daos\RoundDaoInterface;
use Models\Answer;
use Models\Round;
use Models\Vote;
use Repositories\AnswerRepositoryInterface;
use Repositories\CardRepositoryInterface;
use Repositories\VoteRepositoryInterface;

class RoundRepository implements RoundRepositoryInterface {
    private RoundDaoInterface $roundDao;
    private AnswerRepositoryInterface $answerRepository;
    private CardRepositoryInterface $cardRepository;
    private VoteRepositoryInterface $voteRepository;

    public function __construct(
        RoundDaoInterface $roundDao,
        AnswerRepositoryInterface $answerRepository,
        CardRepositoryInterface $cardRepository,
        VoteRepositoryInterface $voteRepository
    ) {
        $this->roundDao = $roundDao;
        $this->answerRepository = $answerRepository;
        $this->cardRepository = $cardRepository;
        $this->voteRepository = $voteRepository;
    }

    public function getAll(): array(){
        return $this->roundDao->getAll();
    }

    public function getById($id) {
        $round = $this->roundDao->getById($id);

        if(!$round) {
            return null;
        }

        $answers = $this->answerRepository->getByRoundId($round->getId());

        $round->setAnswers($answers);

        $votes = $this->voteRepository->getByRoundId($round->getId());

        $round->setVotes($votes);

        $card = $this->cardRepository->getById($round->getCardId());

        $round->setCard($card);

        return $round;
    }

    public function getByGameId($gameId) {
        $rounds = $this->roundDao->getByGameId($gameId);

        if(!$rounds) {
            return null;
        }

        $arr = [];

        foreach($rounds as $round) {
            $arr[] = $this->getById($round->getId());
        }

        return $arr;
    }

    public function insertRound(Round $round) : Round {
        $round = $this->roundDao->insert($round);

        $answers = $round->getAnswers();

        if($answers) {
            $round->setAnswers($this->answerRepository->insertAnswers($answers));
        }

        $votes = $round->getVotes();

        if($votes) {
            $round->setVotes($this->answerRepository->insertVotes($votes));
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

        $votes = $round->getVotes();

        if($votes) {
            $round->setVotes($this->voteRepository->updateVotes($votes));
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

        $votes = $round->getVotes();

        if($votes) {
            $round->setVotes($this->voteRepository->deleteVotes($votes));
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