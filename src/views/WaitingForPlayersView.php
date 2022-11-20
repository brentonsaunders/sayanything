<?php
namespace Views;

use Models\Game;

class WaitingForPlayersView extends GameView {
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

        if($this->game->getNumberOfPlayers() < 4) {
            echo '<div id="game-state">Waiting for more players ...</div>';
        } else if($this->game->isCreator($this->playerId)) {
            echo '<div id="game-state">Waiting for you to start the game ...</div>';
        } else {
            $creator = $this->game->getCreator();

            echo '<div id="game-state">Waiting for ' . $creator->getName() . ' to start the game ...</div>';
        }

        echo '</div>';
        echo '<div class="middle"></div>';
        echo '<div class="bottom">';

        if($this->game->getNumberOfPlayers() >= 4 &&  $this->game->isCreator($this->playerId)) {
            echo '<form action="' . $this->game->getId() . '/start" method="post">';
            echo '<button type="submit">Start Game</button>';
            echo '</form>';
        }

        echo '</div>';
        echo '</div>';
    }
}
