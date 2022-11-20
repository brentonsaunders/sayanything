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
        echo '<div id="game">';
        echo '<div class="top">';

        parent::heading($this->game);
        parent::players($this->game, $this->playerId);

        echo '<div id="game-state">';

        $winnerId = $this->game->getCurrentRound()->getChosenAnswerPlayerId();

        $winner = $this->game->getPlayer($winnerId);

        $roundNumber = count($this->game->getRounds());

        echo $winner->getName() . ' Wins Round ' . $roundNumber . '!<br>';
        
        echo 'Waiting for the next round to begin ...</div>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);

        echo '</div>';
        echo '<div class="middle">';

        $answers = $this->game->getCurrentRound()->getAnswers();
        $chosenAnswerId = $this->game->getCurrentRound()->getChosenAnswerId();

        parent::selectOMatic($this->game, $answers, $chosenAnswerId, true);

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
        $votes = $this->game->getCurrentRound()->getPlayerVotes($this->playerId);

        usort($answers, function($a, $b) use($chosenAnswerId) {
            if($a->getId() == $chosenAnswerId) {
                return -1;
            }
            
            return 1;
        });

        echo '<div class="results" id="answers">';

        foreach($answers as $answer) {
            $player = $this->game->getPlayer($answer->getPlayerId());

            echo '<div class="answer ' . $player->getToken() . '">';
            echo '<div class="votes">';
            echo "</div>";
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
    }
}
