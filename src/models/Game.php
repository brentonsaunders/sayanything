<?php
namespace Models;

class Game {
    private $id = null;
    private $friendlyId = null;
    private $name = null;
    private $creatorId = null;
    private $roundId = null;
    private $state = null;
    private $timeUpdated = null;
    private $dateCreated = null;
    private $players = null;

    public function __construct($id, $friendlyId, $name, $creatorId, $roundId,
        $state, $timeUpdated, $dateCreated) {
        $this->id = $id;
        $this->friendlyId = $friendlyId;
        $this->name = $name;
        $this->creatorId = $creatorId;
        $this->roundId = $roundId;
        $this->state = $state;
        $this->timeUpdated = $timeUpdated;
        $this->dateCreated = $dateCreated;
    }

    public function getId() {
        return $this->id;
    }

    public function getFriendlyId() {
        return $this->friendlyId;
    }

    public function getName() {
        return $this->name;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function getRoundId() {
        return $this->roundId;
    }

    public function getState() {
        return $this->state;
    }

    public function getUpdateTime() {
        return $this->updateTime;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFriendlyId($friendlyId) {
        $this->friendlyId = $friendlyId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCreatorId($creatorId) {
        $this->creatorId = $creatorId;
    }

    public function setRoundId($roundId) {
        $this->roundId = $roundId;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setUpdateTime($updateTime) {
        $this->updateTime = $updateTime;
    }

    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    public function setPlayers($players) {
        $this->players = $players;
    }
}