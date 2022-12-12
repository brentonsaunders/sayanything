<?php
namespace Models;

class Vote {
    private $id = null;
    private $roundId = null;
    private $playerId = null;
    private $answer1Id = null;
    private $answer2Id = null;

    public function __construct() {}

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getRoundId() {
        return $this->roundId;
    }

    public function setRoundId($roundId) {
        $this->roundId = $roundId;
    }

    public function getPlayerId() {
        return $this->playerId;
    }

    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    public function getAnswer1Id() {
        return $this->answer1Id;
    }

    public function setAnswer1Id($answer1Id) {
        $this->answer1Id = $answer1Id;
    }

    public function getAnswer2Id() {
        return $this->answer2Id;
    }

    public function setAnswer2Id($answer2Id) {
        $this->answer2Id = $answer2Id;
    }
}
