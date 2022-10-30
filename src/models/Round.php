<?php
namespace Models;

use Models\Answer;
use Models\Card;

class Round {
    private $id = null;
    private $gameId = null;
    private $activePlayerId = null;
    private $cardId = null;
    private $questionId = null;
    private $chosenAnswerId = null;
    
    private $card = null;
    private $answers = null;

    public function __construct($id, $gameId, $activePlayerId, $cardId,
        $questionId, $chosenAnswerId) {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->activePlayerId = $activePlayerId;
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

    public function getActivePlayerId() {
        return $this->activePlayerId;
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function setActivePlayerId($activePlayerId) {
        $this->activePlayerId = $activePlayerId;
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
}