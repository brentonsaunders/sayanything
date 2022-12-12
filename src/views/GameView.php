<?php
namespace Views;

use Models\Game;
use Models\Round;

abstract class GameView implements View {
    protected Game $game;
    private $dontRefreshBody = false;

    public function __construct(Game $game) {
        $this->game = $game;
    }

    protected function dontRefreshBody($dontRefreshBody) {
        $this->dontRefreshBody = $dontRefreshBody;
    }

    protected function playerTokens($playerId, $winnerIds = [], $roundId = null) {
        echo '<div id="players">';

        $players = $this->game->getPlayers();

        $notWaiting = false;
        
        if($playerId) {
            $notWaiting = !$this->game->getPlayer($playerId)->getMustWaitForNextRound();
        }

        foreach($players as $player) {
            $me = '';
            $judge = '';
            $winner = '';

            if($player->getId() == $playerId) {
                $me = 'me';
            }

            if($this->game->isJudge($player->getId(), $roundId)) {
                $judge = 'judge';
            }

            if($winnerIds && in_array($player->getId(), $winnerIds)) {
                $winner = "winner";
            }

            // Only show the winner of a round if the player is a part of the game
            if(
               $playerId !== null &&
               $notWaiting &&
               $winnerIds &&
               in_array($player->getId(), $winnerIds)
               ) {
                $winner = 'winner';
            }

            echo '<div class="player ' . $me . ' ' . $judge . ' ' . $winner . '">';

            echo '<div class="token ' . $player->getToken() . '"></div>';
            echo '<div class="name">' . $player->getName() . '</div>';

            echo '</div>';
        }

        echo '</div>';
    }

    protected function gameState($state) {
        echo '<div id="game-state">' . $state . '</div>';
    }

    protected function countdownTimer($secondsGiven) {
        $secondsElapsed = $this->game->secondsSinceLastUpdate();

        $secondsLeft = max($secondsGiven - $secondsElapsed, 0);

        echo '<div id="countdown-timer">' . $secondsLeft . '</div>';
    }

    protected function selectOMatic($answers, $chosenAnswerId, $disabled = false) {
        $tokensOfPlayersWhoAnswered = [];
        $spaces = [];
        $chosenAnswerPlayerToken = '';

        foreach($answers as $answer) {
            $player = $this->game->getPlayer($answer->getPlayerId());

            $token = $player->getToken();

            $checked = '';

            if($answer->getId() == $chosenAnswerId) {
                $chosenAnswerPlayerToken = $token;

                if(!$disabled) {
                    $checked = 'checked';
                }
            }

            $tokensOfPlayersWhoAnswered[] = $token;

            $spaces[] = '<input ' . (($disabled) ? 'disabled' : '') . ' ' . $checked . ' form="choosing-answer" onchange="$(\'#choosing-answer\').submit();" onclick="$(\'#select-o-matic\').attr(\'class\', \'' . $token . '\');" name="answerId" id="' . $token . '" type="radio" value="' . $answer->getId() . '"><label for="' . $token . '" class="space ' . $token . '"></label>';
        }

        $tokens = array_diff(Player::getTokens(), $tokensOfPlayersWhoAnswered);

        foreach($tokens as $token) {
            $spaces[] = '<input disabled name="answerId" id="' . $token . '" type="radio"><label for="' . $token . '" class="inactive space ' . $token . '"></label>';
        }

        echo '<form data-dont-refresh="true" id="choosing-answer" action="' . $this->game->getId() . '/chooseAnswer" method="post"></form>';
        echo '<div id="select-o-matic" class="' . $chosenAnswerPlayerToken . '">';

        foreach($spaces as $space) {
            echo $space;
        }

        echo '<div class="arrow"></div>';
        echo '</div>';
    }

    protected function heading($roundId = null) {
        $rounds = $this->game->getRounds();

        echo '<div id="game-heading">';
        echo '<div id="game-name">' . $this->game->getName() . '</div>';

        if($rounds) {
            $roundNumber = $this->game->getRoundNumber($roundId);

            echo '<div id="game-round">' . $roundNumber . '/11</div>';
        }

        echo '</div>';
    }

    protected function head() {
        $this->heading();
    }

    protected function body() {}

    public function render() {
        echo '<div id="game">';
        echo '<div class="head">';

        $this->head();

        echo '</div>';
        echo '<div ';

        if($this->dontRefreshBody) {
            echo 'data-dont-refresh="true"';
        }
        
        echo ' id="game-body" class="body">';

        $this->body();

        echo '</div>';
        echo '</div>';
    }
}
