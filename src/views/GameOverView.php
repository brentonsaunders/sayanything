<?php
namespace Views;

use Models\Game;

class GameOverView extends GameView {
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        parent::__construct($game);

        $this->playerId = $playerId;
    }

    protected function head() {
    }

    protected function body() {
        
    }
}
