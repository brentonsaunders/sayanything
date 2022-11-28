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
        echo '<div class="head">';
        echo "</div>";
        echo '<div class="body">';
        echo '<div id="score-board">';

        echo '<div class="table">';

        echo '<div class="caption">Score for Each Round</div>';

        echo '<div class="head">';
        echo '<div class="row">';

        for($round = 1; $round <= 12; ++$round) {
            echo "<div class=\"col\">$round</div>";
        }

        echo '<div class="col"><span>Total</span></div>';
        echo "</div>";
        echo "</div>";

        echo '<div class="body">';

        $tokens = Player::getTokens();

        foreach($tokens as $token) {
            echo "<div class=\"row $token\">";
            echo "<div class=\"col\"></div>";

            for($round = 1; $round <= 12; ++$round) {
                echo "<div class=\"col\"><span>0</span></div>";
            }

            echo '<div class="col"><span>0</span></div>';
            echo "</div>";
        }

        echo "</div>";

        echo "</div>";

        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}