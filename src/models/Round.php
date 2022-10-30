<?php
namespace Models;

use Models\Answer;
use Models\Card;
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

    public function addAnswer(Answer $answer) {
        foreach($this->answers as &$otherAnswer) {
            if($otherAnswer->getPlayerId() == $answer->getPlayerId()) {
                $otherAnswer = $answer;

                return true;
            }
        }

        $this->answers[] = $answer;
    }

    public function addVote(Vote $vote) {
        $this->votes[] = $vote;
    }
}