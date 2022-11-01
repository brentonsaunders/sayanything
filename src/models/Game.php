<?php
namespace Models;

class Game {
    const WAITING_FOR_PLAYERS = "waiting-for-players";
    const GAME_STARTED = "game-started";
    const ASKING_QUESTION = "asking-question";
    const ANSWERING_QUESTION = "answering-question";
    const VOTING = "voting";
    const RESULTS = "results";
    
    private $id = null;
    private $name = null;
    private $creatorId = null;
    private $state = null;
    private $timeUpdated = null;
    private $timeCreated = null;

    private $players = null;
    private $rounds = null;

    public function __construct($id, $name, $creatorId, $state, $timeUpdated,
        $timeCreated) {
        $this->id = $id;
        $this->name = $name;
        $this->creatorId = $creatorId;
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

    public function getUsedTokens() {
        $tokens = [];

        if($this->players) {
            foreach($this->players as $player) {
                $tokens[] = $player->getToken();
            }
        }

        return $tokens;
    }

    public function getAvailableTokens() {
        $allTokens = Player::getTokens();
    
        $usedTokens = $this->getUsedTokens();

        $remainingTokens = array_diff($allTokens, $usedTokens);

        return $remainingTokens;
    }

    public function addPlayer($playerName, $playerToken) {
        if($this->players) {
            $numPlayers = count($this->players);
        } else {
            $numPlayers = 0;

            $this->players = [];
        }

        $this->players[] = new Player(null, $this->id, $playerName, $playerToken,
            $numPlayers, null);
    }

    public function addRound($judgeId, $cardId) {
        $this->rounds[] = new Round(null, $this->id, $judgeId, $cardId, null, null);
    }

    public function hasPlayer($playerId) {
        if(!$this->players) {
            return false;
        }

        foreach($this->players as $player) {
            if($player->getId() === $playerId) {
                return true;
            }
        }

        return false;
    }

    public function getNextPlayer() {
        $judge = $this->getJudge();

        if(!$judge) {
            return null;
        }

        $numPlayers = count($this->players);

        $turn = $judge->getTurn();

        $nextTurn = ($turn + 1) % $numPlayers;

        while($this->players[$nextTurn]->getSkipTurn()) {
            $this->players[$nextTurn]->setSkipTurn(false);

            $nextTurn = ($nextTurn + 1) % $numPlayers;
        }

        return $this->players[$nextTurn];
    }

    public function getCurrentRound() {
        return end($this->rounds);
    }

    public function isJudge($playerId) {
        $judge = $this->getJudge();

        return $judge !== null && $judge->getId() === $playerId;
    }

    public function getJudge() {
        if(!$this->rounds) {
            return null;
        }

        $thisRound = $this->getCurrentRound();

        $judgeId = $thisRound->getJudgeId();

        foreach($this->players as $player) {
            if($player->getId() === $judgeId) {
                return $player;
            }
        }

        return null;
    }

    public function everyPlayerHasAnswered() {
        $round = $this->getCurrentRound();

        if(!$round) {
            return false;
        }

        $numPlayers = count($this->players);

        $answers = $round->getAnswers();

        if(!$answers) {
            return false;
        }

        $numAnswers = count($answers);

        return $numAnswers === $numPlayers - 1;
    }

    public function everyPlayerHasVoted() {
        $round = $this->getCurrentRound();

        if(!$round) {
            return false;
        }

        $numPlayers = count($this->players);

        $votes = $round->getVotes();

        if(!$votes) {
            return false;
        }

        $numVotes = count($votes);

        return $numVotes === 2 * ($numPlayers - 1);
    }

    public function judgeHasChosenAnswer() {
        $round = $this->getCurrentRound();

        if(!$round) {
            return false;
        }

        return $round->getChosenAnswerId() !== null;
    }

    public function secondsSinceLastUpdate() {
        return strtotime("now") - strtotime($this->timeUpdated);
    }
}