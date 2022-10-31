<?php
namespace Models;

use Models\Answer;
use Models\Card;
use Models\Question;
use Models\Vote;

class Round {
    private $id = null;
    private $gameId = null;
    private $judgeId = null;
    private $cardId = null;
    private $questionId = null;
    private $chosenAnswerId = null;
    
    private $card = null;
    private $answers = null;
    private $votes = null;

    public function __construct($id, $gameId, $judgeId, $cardId,
        $questionId, $chosenAnswerId) {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->judgeId = $judgeId;
        $this->cardId = $cardId;
        $this->questionId = $questionId;
        $this->chosenAnswerId = $chosenAnswerId;
    }

    public function getId() {
        return $this->id;
    }

    public function getGameId() {
        return $this->gameId;
    }

    public function getJudgeId() {
        return $this->judgeId;
    }

    public function getCardId() {
        return $this->cardId;
    }

    public function getQuestionId() {
        return $this->questionId;
    }

    public function getChosenAnswerId() {
        return $this->chosenAnswerId;
    }

    public function getCard() {
        return $this->card;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function getVotes() {
        return $this->votes;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function setJudgeId($judgeId) {
        $this->judgeId = $judgeId;
    }

    public function setCardId($cardId) {
        $this->cardId = $cardId;
    }

    public function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    public function setChosenAnswerId($chosenAnswerId) {
        $this->chosenAnswerId = $chosenAnswerId;
    }

    public function setCard($card) {
        $this->card = $card;
    }

    public function setAnswers($answers) {
        $this->answers = $answers;
    }

    public function setVotes($votes) {
        $this->votes = $votes;
    }

    public function addAnswer($playerId, $answer) {
        if(!$this->answers) {
            $this->answers = [];
        }

        foreach($this->answers as $key => $otherAnswer) {
            if($otherAnswer->getPlayerId() == $playerId) {
                $this->answers[$key]->setAnswer($answer);

                return true;
            }
        }

        $this->answers[] = new Answer(null, $playerId, $this->id, $answer);
    }

    public function addVotes($playerId, $answerId1, $answerId2) {
        // $this->votes[] = $vote;
    }

    public function hasQuestion($questionId) {
        if(!$this->card) {
            return false;
        }

        $questions = $this->card->getQuestions();

        if(!$questions) {
            return false;
        }

        foreach($questions as $question) {
            if($question->getId() === $questionId) {
                return true;
            }
        }

        return false;
    }

    public function hasAnswer($answerId) {
        if(!$this->answers) {
            return false;
        }

        foreach($this->answers as $answer) {
            if($answer->getId() === $answerId) {
                return true;
            }
        }

        return false;
    }

    public function askRandomQuestion() {
        if($this->questionId) {
            return;
        }

        $questions = $this->card->getQuestions();

        shuffle($questions);

        $this->questionId = $questions[0]->getId();
    }

    public function chooseAnswer($answerId) {
        $this->chosenAnswerId = $answerId;
    }
}