<?php
namespace Views;

use Models\Game;

class WaitingForPlayersView extends GameView {
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        parent::__construct($game);

        $this->playerId = $playerId;
    }

    protected function body() {
        $isCreator = $this->game->isCreator($this->playerId);
        $creatorName = $this->game->getCreator()->getName();
        $numPlayers = $this->game->getNumberOfPlayers();

        parent::playerTokens($this->playerId);

        if($numPlayers < Game::MIN_PLAYERS) {
            parent::gameState("Waiting for more players ...");
        } else if($isCreator) {
            parent::gameState("Waiting for you to start the game ...");

            $this->startGameButton();
        } else {
            parent::gameState("Waiting for $creatorName to start the game ...");
        }
    }

    private function startGameButton() {
        echo '<form action="' . $this->game->getId() . '/start" method="post">';
        echo '<button id="start-game-button" type="submit">Start Game</button>';
        echo '</form>';
    }
}
