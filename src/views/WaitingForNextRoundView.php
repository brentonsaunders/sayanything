<?php
namespace Views;

use Models\Game;

class WaitingForNextRoundView extends GameView {
    private $playerId;

    public function __construct(Game $game, $playerId) {
        parent::__construct($game);

        $this->playerId = $playerId;
    }

    protected function body() {
        parent::playerTokens($this->playerId);

        parent::gameState("Waiting for a new round to begin ...");
    }
}
