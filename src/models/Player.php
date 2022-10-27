<?php
namespace Models;

class Player {
    private $id = null;
    private $gameId = null;
    private $name = null;
    private $token = null;

    public function __construct($id, $gameId, $name, $token) {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->name = $name;
        $this->token = $token;
    }

    public function getId() {
        return $this->id;
    }

    public function getGameId() {
        return $this->gameId;
    }

    public function getName() {
        return $this->name;
    }

    public function getToken() {
        return $this->token;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setToken($token) {
        $this->token = $token;
    }
}