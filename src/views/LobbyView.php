<?php
namespace Views;

use Models\Player;

class LobbyView extends MainView {
    private $games = [];

    public function __construct($games) {
        $this->games = $games;
    }

    protected function main() {
        echo '<div id="game">';
        echo '<div class="top">';

        $this->myGames();

        echo '</div>';
        echo '<div class="middle">';
        echo '</div>';
        echo '<div class="bottom">';
        echo '<button class="button" onclick="showModal(\'create-game\');">Create Game</button>';
        echo '</div>';
        echo '</div>';

        $this->createGameModal();
    }

    private function myGames() {
        echo '<div id="my-games">';
        echo "<h2>My Games</h2>";

        if(count($this->games) === 0) {
            echo "<p>No games yet</p>";
        } else {
            foreach($this->games as $game) {
                echo '<a class="' . $game["playerToken"] . '" href="' . $game["gameId"] . '">';
                echo '<div class="left"><div class="token ' . $game["playerToken"] . '"></div></div>';
                echo '<div class="center"><div class="game-name">' . $game["gameName"] . '</div><div class="round">';

                if($game["round"]) {
                    echo $game["round"] . "/11";
                } else {
                    echo "Waiting for players";
                }

                echo '</div></div>';
                echo '<div class="right"><div class="arrow"></div></div>';
                echo "</a>";
            }
        }

        echo "</div>";
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
