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
    private $mustWaitForNextRound = null;
    private $isWinner = null;
    private $isJudge = null;
    private $isMe = null;

    public function __construct() {}

    public static function getTokens() {
        return [
            self::MARTINI_GLASS,
            self::DOLLAR_SIGN,
            self::HIGH_HEELS,
            self::COMPUTER,
            self::CAR,
            self::FOOTBALL,
            self::GUITAR,
            self::CLAPPERBOARD
        ];
    }

    public static function isToken($token) {
        return in_array($token, self::getTokens());
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

    public function getMustWaitForNextRound() {
        return $this->mustWaitForNextRound;
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

    public function setMustWaitForNextRound($mustWaitForNextRound) {
        $this->mustWaitForNextRound = $mustWaitForNextRound;
    }

    public function getIsWinner() {
        return $this->isWinner;
    }

    public function setIsWinner($isWinner) {
        $this->isWinner = $isWinner;
    }

    public function getIsJudge() {
        return $this->isJudge;
    }

    public function setIsJudge($isJudge) {
        $this->isJudge = $isJudge;
    }

    public function getIsMe() {
        return $this->isMe;
    }

    public function setIsMe($isMe) {
        $this->isMe = $isMe;
    }
}
