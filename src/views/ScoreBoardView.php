<?php
namespace Views;

use Models\Game;
use Models\Player;
use Models\ScoreBoard;

class ScoreBoardView implements View {
    private Game $game;
    private ScoreBoard $scoreBoard;

    public function __construct(Game $game, ScoreBoard $scoreBoard) {
        $this->game = $game;
        $this->scoreBoard = $scoreBoard;
    }

    public function render() {
        echo '<div id="game">';
        echo '<div class="head"></div><div class="body">';

        echo '<div id="score-board">';
        echo '<div class="score-board-table">';

        $playerIds = $this->scoreBoard->getPlayerIds();

        foreach($playerIds as $playerId) {
            $player = $this->game->getPlayer($playerId);
            $playerName = $player->getName();
            $token = $player->getToken();

            echo "<div class=\"score-board-row $token\">";
            echo "<div class=\"score-board-col\">$playerName</div>";

            for($round = 1; $round <= 12; ++$round) {
                echo '<div class="score-board-col score">0</div>';
            }

            echo '<div class="score-board-col total">0</div>';
            echo '</div>';
        }

        echo '</div>';
        
        echo '</div>';
        echo "</div>";
        echo "</div>";
    }
}