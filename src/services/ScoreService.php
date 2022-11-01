<?php
namespace Services;

use Models\Game;
use Models\Round;
use Models\Player;
use Models\ScoreBoard;

class ScoreService {
    private Game $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function getScoreBoard() {
        $players = $this->game->getPlayers();

        if(!$players) {
            return null;
        }

        $scoreBoard = new ScoreBoard($players);

        $rounds = $this->game->getRounds();

        if(!$rounds) {
            return $scoreBoard;
        }

        foreach($rounds as $roundNumber => $round) {
            foreach($players as $player) {
                if($round->isJudge($player->getId())) {
                    $score = $this->calculateJudgeScore($round);
                } else {
                    $score = $this->calculateScore($round, $player);
                }

                $scoreBoard->setScore($player, $roundNumber, $score);
            }
        }

        return $scoreBoard;
    }

    private function calculateJudgeScore(Round $round) {
        $judge = $this->game->getJudge();

        // How many tokens are on the chosen answer
        $votes = $round->getVotes();

        if(!$votes) {
            return null;
        }

        $numTokens = 0;

        foreach($votes as $vote) {
            if($vote->getAnswerId() === $round->getChosenAnswerId()) {
                ++$numTokens;
            }
        }

        return min(3, $numTokens);
    }

    private function calculateScore(Round $round, Player $player) {
        $votes = $round->getPlayerVotes($player->getId());

        if(!$votes) {
            return null;
        }

        $numTokens = 0;

        foreach($votes as $vote) {
            if($vote->getAnswerId() === $round->getChosenAnswerId()) {
                ++$numTokens;
            }
        }

        if($round->wroteChosenAnswer($player->getId())) {
            $numTokens += 1;
        }

        return min(3, $numTokens);
    }
}