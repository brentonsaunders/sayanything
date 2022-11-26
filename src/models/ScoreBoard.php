<?php
namespace Models;

class ScoreBoard {
    private $scores;
    private $numRounds;

    public function __construct($players, $numRounds = 11) {
        $this->scores = [];
        $this->numRounds = $numRounds;

        foreach($players as $player) {
            $this->scores[$player->getId()] = array_fill(0, $numRounds, null);
        }
    }

    public function getPlayerIds() {
        return array_keys($this->scores);
    }

    public function getScores(Player $player) {
        return $this->scores[$player->getId()];
    }

    public function getScore(Player $player, $round) {
        return $this->scores[$player->getId()][$round];
    }

    public function setScore(Player $player, $round, $score) {
        $this->scores[$player->getId()][$round] = $score;
    }
}