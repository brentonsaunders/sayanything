<?php
namespace Models;

class Answer {
    private $id = null;
    private $playerId = null;
    private $roundId = null;
    private $answer = null;

    public function __construct($id, $playerId, $roundId, $answer) {
        $this->id = $id;
        $this->playerId = $playerId;
        $this->roundId = $roundId;
        $this->answer = $answer;
    }

    public function getId() {
        return $this->id;
    }

    public function getPlayerId() {
        return $this->playerId;
    }

    public function getRoundId() {
        return $this->roundId;
    }

    public function getAnswer() {
        return $this->answer;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    public function setRoundId($roundId) {
        $this->roundId = $roundId;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;
    }
}