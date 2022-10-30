<?php
namespace Models;

class Vote {
    private $id = null;
    private $roundId = null;
    private $playerId = null;
    private $answerId = null;

    public function __construct($id, $roundId, $playerId, $answerId) {
        $this->id = $id;
        $this->roundId = $roundId;
        $this->playerId = $playerId;
        $this->answerId = $answerId;
    }

    public function getId() {
        return $this->id;
    }

    public function getRoundId() {
        return $this->roundId;
    }

    public function getPlayerId() {
        return $this->playerId;
    }

    public function getAnswerId() {
        return $this->answerId;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setRoundId($roundId) {
        $this->roundId = $roundId;
    }

    public function setPlayerId($playerId) {
        $this->playerId = $playerId;
    }

    public function setAnswerId($answerId) {
        $this->answerId = $answerId;
    }
}