<?php
namespace Models;

class Game {
    const WAITING_FOR_PLAYERS = "waiting-for-players";
    const GAME_STARTED = "game-started";
    const ASKING_QUESTION = "asking-question";
    const ANSWERING_QUESTION = "answering-question";
    const VOTING = "voting";
    const RESULTS = "results";
    const GAME_OVER = "game-over";

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
    private $currentRoundId = null;
    private $timeUpdated = null;
    private $timeCreated = null;

    private $players = null;
    private $rounds = null;

    public function __construct() {}

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function setCreatorId($creatorId) {
        $this->creatorId = $creatorId;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getCurrentRoundId() {
        return $this->currentRoundId;
    }

    public function setCurrentRoundId($currentRoundId) {
        $this->currentRoundId = $currentRoundId;
    }

    public function getTimeUpdated() {
        return $this->timeUpdated;
    }

    public function setTimeUpdated($timeUpdated) {
        $this->timeUpdated = $timeUpdated;
    }

    public function getTimeCreated() {
        return $this->timeCreated;
    }

    public function setTimeCreated($timeCreated) {
        $this->timeCreated = $timeCreated;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function setPlayers($players) {
        $this->players = [];

        foreach($players as $player) {
            $this->players[$player->getId()] = $player;
        }
    }

    public function getRounds() {
        return $this->rounds;
    }

    public function setRounds($rounds) {
        $this->rounds = $rounds;
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

    public function getRoundNumber($roundId = null) {
        if(!$this->rounds) {
            return null;
        }

        if(!$roundId) {
           return count($this->rounds);
        }

        $rounds = $this->rounds;

        usort($rounds, function($a, $b) {
            return $a->getId() - $b->getId();
        });

        for($i = 0; $i < count($rounds); ++$i) {
            if($rounds[$i]->getId() == $roundId) {
                return $i + 1;
            }
        }

        return null;
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

    public function addPlayer(Player $player) {
        if(!$this->players) {
            $this->players = [];
        }

        $this->players[$player->getId()] = $player;
    }

    public function addRound(Round $round) {
        if(!$this->rounds) {
            $this->rounds = [];
        }

        $this->rounds[$round->getId()] = $round;
    }

    public function getPlayer($playerId) {
        return $this->players[$playerId] ?? null;
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

        return $this->rounds[$this->currentRoundId];
    }

    public function getRound($roundId) {
        if(!$this->rounds) {
            return null;
        }

        foreach($this->rounds as $round) {
            if($round->getId() == $roundId) {
                return $round;
            }
        }

        return null;
    }

    public function isJudge($playerId, $roundId = null) {
        $judge = $this->getJudge($roundId);

        return $judge !== null && $judge->getId() == $playerId;
    }

    public function getJudge($roundId = null) {
        if(!$this->rounds) {
            return null;
        }

        if($roundId) {
            $round = $this->getRound($roundId);
        } else {
            $round = $this->getCurrentRound();
        }

        $judgeId = $round->getJudgeId();

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
            return true;
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

    public function isOver() {
        return $this->getRoundNumber() >= 11;
    }
}