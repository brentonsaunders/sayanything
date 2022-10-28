<?php
namespace Models;

class Player {
    const MARTINI_GLASS = "martini-glass";
    const DOLLAR_SIGN   = "dollar-sign";
    const HIGH_HEELS    = "high-heels";
    const COMPUTER      = "computer";
    const CAR           = "car";
    const FOOTBALL      = "football";
    const GUITAR        = "guitar";
    const CLAPPERBOARD  = "clapperboard";

    private $id = null;
    private $gameId = null;
    private $name = null;
    private $token = null;
    private $turn = null;
    private $skipTurn = null;

    public function __construct($id, $gameId, $name, $token, $turn,
        $skipTurn) {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->name = $name;
        $this->token = $token;
        $this->turn = $turn;
        $this->skipTurn = $skipTurn;
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

    public function getTurn() {
        return $this->turn;
    }

    public function getSkipTurn() {
        return $this->skipTurn;
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

    public function setTurn($turn) {
        $this->turn = $turn;
    }

    public function setSkipTurn($skipTurn) {
        $this->skipTurn = $skipTurn;
    }
}