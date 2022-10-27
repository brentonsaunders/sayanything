<?php
namespace Models;

class Round {
    private $id = null;
    private $gameId = null;
    private $activePlayerId = null;
    private $questionId = null;
    private $chosenAnswerId = null;

    public function __construct($id, $gameId, $activePlayerId, $questionId,
        $chosenAnswerId) {
        $this->id = $id;
        $this=>gameId = $gameId;
        $this->activePlayerId = $activePlayerId;
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

    public function getQuestionId() {
        return $this->questionId;
    }

    public function getChosenAnswerId() {
        return $this->chosenAnswerId;
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

    public function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    public function setChosenAnswerId($chosenAnswerId) {
        $this->chosenAnswerId = $chosenAnswerId;
    }
}