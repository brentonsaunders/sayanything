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
        $answers = $round->getAnswers();
        $chosenAnswerId = $round->getChosenAnswerId();

        if(!$answers || count($answers) === 1) {
            // Judge wins two points if ony one or no one answers the question
            return 2;
        } else if(!$chosenAnswerId) {
            // Judge loses two points if they don't choose a favorite answer
            // and there is more than one answer
            return -2;
        }

        // How many tokens are on the chosen answer
        $votes = $round->getVotes();

        if(!$votes) {
            // Judge wins two points if no player has voted on an answer
            return 2;
        }

        $numTokens = 0;

        foreach($votes as $vote) {
            if($vote->getAnswerId() === $round->getChosenAnswerId()) {
                ++$numTokens;
            }
        }

        // Judge wins a point for every token on the chosen answer
        return min(3, $numTokens);
    }

    private function calculateScore(Round $round, Player $player) {
        $answers = $round->getAnswers();

        // If no one answers the question, every player loses a point
        if(!$answers) {
            return -1;
        }

        $lostPoints = 0;

        if(!$round->getPlayerAnswer($player->getId())) {
            // Player loses a point if they don't answer the question
            ++$lostPoints;
        }

        $votes = $round->getVotesFromPlayer($player->getId());

        // Player loses a point for every time they don't vote
        if(!$votes) {
            $lostPoints += 2;
        } else if(count($votes) === 1) {
            // This should never happen because votes are automatically submitted in
            // twos
            ++$lostPoints;
        }

        // But the player can't lose more than two points in a round
        $lostPoints = min(2, $lostPoints);

        $chosenAnswerId = $round->getChosenAnswerId();

        // If the judge didn't choose an answer, then the answer with the most tokens
        // automatically becomes the "chosen answer"
        if(!$chosenAnswerId) {
            $chosenAnswerId = array_map(function($answer) {
                return $answer->getId();
            }, $round->getTopAnswers());
        }

        $numTokens = -$lostPoints;

        foreach($votes as $vote) {
            if(is_array($chosenAnswerId) && in_array($vote->getAnswerId(), $chosenAnswerId)) {
                ++$numTokens;
            } else if($vote->getAnswerId() == $chosenAnswerId) {
                ++$numTokens;
            }
        }

        // If the judge has chosen a favorite answer, then the player who wrote it
        // gets a point
        if($round->wroteChosenAnswer($player->getId())) {
            $numTokens += 1;
        }

        return min(3, $numTokens);
    }
}