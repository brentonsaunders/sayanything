<?php
namespace Views;

use Models\Game;

class ResultsView extends GameView {
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
        echo '<div class="middle">';

        parent::heading($this->game);

        parent::selectOMatic($this->game, $answers, $chosenAnswerId, true);

        echo '<div id="game-state">';

        echo $winner->getName() . ' Wins Round ' . $roundNumber . '!<br>';
        
        echo 'Waiting for the next round to begin ...</div>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);

        parent::players($this->game, $this->playerId);
        
        $this->answers($answers, $chosenAnswerId);
        
        echo '</div>';
        echo '<div class="bottom">';

        if($this->game->isCreator($this->playerId) && $this->game->secondsSinceLastUpdate() >= 30) {
            echo '<form action="' . $this->game->getId() . '/nextRound" method="post">';
            echo '<button type="submit">Next Round</button>';
            echo '</form>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    private function answers($answers, $chosenAnswerId) {
        $votes = $this->game->getCurrentRound()->getVotes();

        $votesForAnswer = function($answerId) use($votes) {
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

            foreach($votes as $vote) {
                if($vote->getAnswerId() == $answer->getId()) {
                    $player = $this->game->getPlayer($vote->getPlayerId());

                    echo '<div class="token ' . $player->getToken() . '"></div>';
                }
            }

            echo '</div>';
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
    }
}
