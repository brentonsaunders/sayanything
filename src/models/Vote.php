<?php
namespace Models;

class Vote {
    private $id = null;
    private $playerId = null;
    private $roundId = null;
    private $answerId = null;

    public function __construct($id, $playerId, $roundId, $answerid) {
        $this->id = $id;
        $this->playerId = $playerId;
        $this->roundId = $roundId;
        $this->answerid = $answerId;
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

    public function getAnswerId() {
        return $this->answerId;
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

    public function setAnswerId($answerId) {
        $this->answerId = $answerId;
    }
}