<?php
namespace Views;

use Models\Game;
use Models\Round;
use Models\ScoreBoard;

class ScoreBoardView implements View {
    private Game $game;
    private ScoreBoard $scoreBoard;

    public function __construct(Game $game, ScoreBoard $scoreBoard) {
        $this->game = $game;
        $this->scoreBoard = $scoreBoard;
    }

    public function render() {
        echo '<div id="score-board">';

        echo '<div class="caption">Score for Each Round</div>';

        echo '<div class="head">';

        for ($i = 1; $i <= 12; ++$i) {
            echo "<div class=\"col\">$i</div>";
        }

        echo '<div class="col">Total</div>';

        echo '</div>';

        echo '<div class="body">';

        $tokens = [
            "guitar",
            "dollar-sign",
            "high-heels",
            "computer",
            "football",
            "martini-glass",
            "clapperboard",
            "car"
        ];

        foreach ($tokens as $token) {
            echo "<div class=\"row $token\">";

            echo '<div class="col"></div>';

            for ($i = 1; $i <= 12; ++$i) {
                echo '<div class="col"><span>0</span></div>';
            }

            echo '<div class="col"><span>0</span></div>';

            echo "</div>";
        }

        echo "</div>";

        echo "</div>";
    }
}