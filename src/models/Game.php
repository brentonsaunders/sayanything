<?php
namespace Models;

class Game {
    const WAITING_FOR_PLAYERS = "waiting-for-players";
    const GAME_STARTED = "game-started";
    const ASKING_QUESTION = "asking-question";
    const ANSWERING_QUESTION = "answering-question";
    const VOTING = "voting";
    const RESULTS = "results";

    const MIN_PLAYERS = 4;
    const MAX_PLAYERS = 8;

    const SECONDS_TO_ASK_QUESTION = 120;
    const SECONDS_TO_ANSWER_QUESTION = 120;
    const SECONDS_TO_VOTE = 120;
    const SECONDS_UNTIL_NEW_ROUND = 120;
    
    private $id = null;
    private $name = null;
    private $creatorId = null;
    private $state = null;
    private $timeUpdated = null;
    private $timeCreated = null;

    private $stateChanged = false;

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

    public function getTimeUpdated() {
        return $this->timeUpdated;
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
        if($state !== $this->state) {
            $this->stateChanged = true;
        }

        $this->state = $state;
    }

    public function setTimeUpdated($timeUpdated) {
        $this->timeUpdated = $timeUpdated;
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

    public function stateHasChanged() {
        return $this->stateChanged;
    }

    public function getCreator() {
        return $this->getPlayer($this->creatorId);
    }

    public function getNumberOfPlayers() {
        return count($this->players);
    }

    public function isCreator($playerId) {
        return $playerId == $this->creatorId;
    }

    public function allowPlayersWaitingForNextRound() {
        if(!$this->players) {
            return;
        }

        foreach($this->players as $player) {
            $player->setMustWaitForNextRound(false);
        }
    }

    public function getRoundNumber() {
        if(!$this->rounds) {
            return null;
        }

        return count($this->rounds);
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

    public function addPlayer($playerName, $playerToken, $skipTurn, $mustWaitForNextRound) {
        if($this->players) {
            $numPlayers = count($this->players);
        } else {
            $numPlayers = 0;

            $this->players = [];
        }

        $this->players[] = new Player(null, $this->id, $playerName, $playerToken,
            $numPlayers, $skipTurn, $mustWaitForNextRound);
    }

    public function addRound($judgeId, $cardId) {
        $this->rounds[] = new Round(null, $this->id, $judgeId, $cardId, null, null);
    }

    public function getPlayer($playerId) {
        if(!$this->players) {
            return null;
        }

        foreach($this->players as $player) {
            if($player->getId() == $playerId) {
                return $player;
            }
        }

        return null;
    }

    public function getPlayerByToken($token) {
        if(!$this->players) {
            return null;
        }

        foreach($this->players as $player) {
            if($player->getToken() == $token) {
                return $player;
            }
        }

        return null;
    }

    public function getPlayerByTurn($turn) {
        if(!$this->players) {
            return null;
        }

        foreach($this->players as $player) {
            if($player->getTurn() == $turn) {
                return $player;
            }
        }

        return null;
    }

    public function hasPlayer($playerId) {
        return $this->getPlayer($playerId) !== null;
    }

    public function getNextPlayer() {
        $judge = $this->getJudge();

        if(!$judge) {
            return null;
        }

        $numPlayers = count($this->players);

        $turn = $judge->getTurn();

        $nextTurn = ($turn + 1) % $numPlayers;

        $nextPlayer = $this->getPlayerByTurn($nextTurn);

        while($nextPlayer->getSkipTurn()) {
            $nextPlayer->setSkipTurn(false);

            $nextTurn = ($nextTurn + 1) % $numPlayers;

            $nextPlayer = $this->getPlayerByTurn($nextTurn);
        }

        return $nextPlayer;
    }

    public function getCurrentRound() {
        if(!$this->rounds) {
            return null;
        }

        usort($this->rounds, function($a, $b) {
            if($a->getId() < $b->getId()) {
                return -1;
            } else if($a->getId() > $b->getId()) {
                return 1;
            }

            return 0;
        });

        return end($this->rounds);
    }

    public function isJudge($playerId) {
        $judge = $this->getJudge();

        return $judge !== null && $judge->getId() == $playerId;
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

    public function lessThanTwoPlayersHaveAnswered() {
        $round = $this->getCurrentRound();

        if(!$round) {
            return false;
        }

        $answers = $round->getAnswers();

        if(!$answers) {
            return false;
        }

        $numAnswers = count($answers);

        return $numAnswers < 2;
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

    public function getPlayedCards() {
        if(!$this->rounds) {
            return null;
        }

        $cards = [];

        foreach($this->rounds as $round) {
            $cards[] = $round->getCard();
        }

        return $cards;
    }

    public function secondsSinceCreated() {
        return strtotime("now") - strtotime($this->timeCreated);
    }

    public function secondsSinceLastUpdate() {
        return strtotime("now") - strtotime($this->timeUpdated);
    }
}