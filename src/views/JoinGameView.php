<?php
namespace Views;

use Models\Game;

class JoinGameView extends GameView {
    private Game $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    public function render() {
        echo '<div id="game">';
        echo '<div class="top">';

        parent::heading($this->game);
        parent::players($this->game, null);

        echo '</div>';
        echo '<div class="middle"></div>';
        echo '<div class="bottom">';
        echo '<button type="button" onclick="showModal(\'join-game\');">Join Game</button>';
        echo '</div>';
        echo '</div>';

        if($this->game->getNumberOfPlayers() < 8) {
            $this->joinGame();
        }
    }

    private function joinGame() {
        $gameId = $this->game->getId();

        echo '<div data-dont-refresh="true" id="join-game" class="modal-overlay">';
        echo '<div class="modal">';
        echo '<form action="$gameId/join" method="post">';
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
