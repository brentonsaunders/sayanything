<?php
namespace Views;

use Models\Player;

class LobbyView extends MainView {
    public function __construct($games) {
    }

    protected function main() {
        echo '<div id="game">';
        echo '<div id="play-area">';
        echo '<button onclick="showModal(\'create-game\');">Create Game</button>';
        echo '</div>';
        echo '</div>';

        $this->createGameModal();
    }

    private function createGameModal() {
        echo '<div data-dont-refresh="true" id="create-game" class="modal-overlay">';
        echo '<div class="modal">';
        echo '<form action="." method="post">';
        echo '<h2>Create Game</h2>';
        echo '<input id="game-name" name="gameName" placeholder="Game Name" type="text">';
        echo '<input id="player-name" name="playerName" placeholder="Your Name" type="text">';
        echo "<h2>Your Token</h2>";
        echo '<div id="tokens">';
    
        $tokens = Player::getTokens();

        foreach($tokens as $token) {
            echo '<label><input type="radio" name="playerToken" value="' . $token . '"><div class="token ' . $token . '"></div></label>';
        }
            
        echo "</div>";
        echo '<button type="submit">OK</button>';
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }

    public function render() {
        parent::render();
    }
}
