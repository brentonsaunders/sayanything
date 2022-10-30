<?php
namespace Models;

class Game {
    const WAITING_FOR_PLAYERS = "waiting-for-players";
    const GAME_STARTED = "game-started";
    const ASKING_QUESTION = "asking-question";
    const ANSWERING_QUESTION = "answering-question";
    
    private $id = null;
    private $name = null;
    private $creatorId = null;
    private $currentRoundId = null;
    private $state = null;
    private $timeUpdated = null;
    private $timeCreated = null;

    private $players = null;
    private $rounds = null;

    public function __construct($id, $name, $creatorId, $currentRoundId,
        $state, $timeUpdated, $timeCreated) {
        $this->id = $id;
        $this->name = $name;
        $this->creatorId = $creatorId;
        $this->currentRoundId = $currentRoundId;
        $this->state = $state;
        $this->timeUpdated = $timeUpdated;
        $this->timeCreated = $timeCreated;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function getCurrentRoundId() {
        return $this->currentRoundId;
    }

    public function getState() {
        return $this->state;
    }

    public function getUpdateTime() {
        return $this->updateTime;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getRounds() {
        return $this->rounds;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCreatorId($creatorId) {
        $this->creatorId = $creatorId;
    }

    public function setCurrentRoundId($currentRoundId) {
        $this->currentRoundId = $currentRoundId;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setUpdateTime($updateTime) {
        $this->updateTime = $updateTime;
    }

    public function setTimeCreated($timeCreated) {
        $this->timeCreated = $timeCreated;
    }

    public function setPlayers($players) {
        $this->players = $players;
    }

    public function setRounds($rounds) {
        $this->rounds = $rounds;
    }
}