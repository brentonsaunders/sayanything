<?php
namespace Views;

use Models\Game;

class GameOverView extends GameView {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    public function render() {
        $answers = $this->game->getCurrentRound()->getAnswers();
        $chosenAnswerId = $this->game->getCurrentRound()->getChosenAnswerId();
        $winnerId = $this->game->getCurrentRound()->getChosenAnswerPlayerId();
        $winner = $this->game->getPlayer($winnerId);
        $roundNumber = count($this->game->getRounds());

        echo '<div id="game" class="results">';
        echo '<div class="top"></div>';
        echo '<div id="results" data-dont-refresh="true" class="middle">';

        parent::heading($this->game);

        parent::players($this->game, $this->playerId);

        echo '<div id="game-state">Game Over.</div>';
            
        $this->answers($answers, $chosenAnswerId);
        
        echo '</div>';
        echo '<div class="bottom"></div>';
        echo '</div>';
    }

    private function answers($answers, $chosenAnswerId) {
        $votes = $this->game->getCurrentRound()->getVotes();

        $votesForAnswer = function($answerId) use($votes) {
            if(!$votes) {
                return [];
            }

            return array_filter($votes, function($vote) use($answerId) {
                return $vote->getAnswerId() == $answerId;
            });
        };

        // Bring the chosen answer to the top, and then sort the rest by number
        // of votes
        usort($answers, function($a, $b) use($chosenAnswerId, $votesForAnswer) {
            if($a->getId() == $chosenAnswerId) {
                return -1;
            } else if($b->getId() == $chosenAnswerId) {
                return 1;
            }

            $numVotesForA = count($votesForAnswer($a->getId()));
            $numVotesForB = count($votesForAnswer($b->getId()));

            return $numVotesForB - $numVotesForA;
        });

        echo '<div class="results" id="answers">';

        foreach($answers as $answer) {
            $player = $this->game->getPlayer($answer->getPlayerId());

            echo '<div class="answer ' . $player->getToken() . '">';
            echo '<div class="votes">';

            if($votes) {
                foreach($votes as $vote) {
                    if($vote->getAnswerId() == $answer->getId()) {
                        $player = $this->game->getPlayer($vote->getPlayerId());

                        echo '<div class="token ' . $player->getToken() . '"></div>';
                    }
                }
            }

            echo '</div>';
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
    }
}
