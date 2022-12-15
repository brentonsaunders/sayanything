<?php
namespace Models;

class Answer {
    private $id = null;
    private $playerId = null;
    private $roundId = null;
    private $answer = null;
    private $player = null;

    public function __construct() {}

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

    public function getPlayer() {
        return $this->player;
    }

    public function setPlayer(Player $player) {
        $this->player = $player;
    }
}