<?php
namespace Views;

use Models\Game;

class WaitingForNextRoundView extends GameView {
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

        echo '<div id="game-state">Waiting for a new round to begin ...</div>';

        echo '</div>';
        echo '<div class="middle"></div>';
        echo '<div class="bottom"></div>';
        echo '</div>';
    }
}
