<?php
namespace Views;

use Models\Game;

class LobbyView implements View {
    private Game $game;
    private $playerHasJoined = null;

    public function __construct(Game $game, $playerHasJoined) {
        $this->game = $game;
        $this->playerHasJoined = $playerHasJoined;
    }

    public function render() {
        echo "<div id=\"lobby\">\n";
        echo "    <div class=\"players\">\n";
        echo "        <h2>Players</h2>\n";
        echo "        <ul>\n";

        $players = $this->game->getPlayers();

        foreach($players as $player) {
            echo "            <li>{$player->getName()}<span>{$player->getToken()}</span></li>\n";
        }

        echo "        </ul>\n";
        echo "    </div>\n";

        if(!$this->playerHasJoined) {
            echo "<div id=\"join-game\">\n";
            echo "<h2>Join Game</h2>\n";
            echo "<form action=\"games/joinGame\" method=\"get\">\n";
            echo "<input name=\"playerName\" placeholder=\"Player Name\" type=\"text\">\n";

            $this->tokenPicker();

            echo "<input type=\"submit\">\n";
            echo "</form>\n";
            echo "</div>\n";
        }

        echo "</div>\n";
    }

    private function tokenPicker() {
        $tokens = $this->game->getAvailableTokens();

        echo "<div id=\"tokens\">\n";

        foreach($tokens as $token) {
            echo "<label class=\"$token\">";
            echo "<input name=\"playerToken\" type=\"radio\" value=\"$token\">";
            echo "</label>\n";
        }

        echo "</div>\n";
    }
}