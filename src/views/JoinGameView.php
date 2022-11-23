<?php
namespace Views;

use Models\Game;

class JoinGameView extends GameView {
    public function __construct(Game $game) {
        parent::__construct($game);
    }

    public function body() {
        $gameIsFull = $this->game->getNumberOfPlayers() >= Game::MAX_PLAYERS;
        
        parent::playerTokens(null);

        if(!$gameIsFull) {
            $this->joinGameButton();
            $this->joinGameModal();
        }
    }

    private function joinGameButton() {
        echo '<button id="join-game-button" type="button" onclick="showModal(\'join-game\');">Join Game</button>';
    }

    private function joinGameModal() {
        echo '<div data-dont-refresh="true" id="join-game" class="modal-overlay">';
        echo '<div class="modal">';
        echo '<form action="' . $this->game->getId() . '/join" method="post">';
        echo '<h2>Join Game</h2>';
        echo '<input id="player-name" name="playerName" placeholder="Your Name" type="text">';
        echo '<h2>Your Token</h2>';
        echo '<div id="tokens">';

        $tokens = $this->game->getAvailableTokens();

        foreach($tokens as $token) {
            echo '<label><input type="radio" name="playerToken" value="' . $token .'"><div class="token ' . $token . '"></div></label>';
        }

        echo '<button type="submit">OK</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
}
