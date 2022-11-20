<?php
namespace Views;

use Models\Game;
use Models\Player;

abstract class GameView implements View {
    protected function heading(Game $game) {
        $rounds = $game->getRounds();

        echo '<div id="game-heading">';
        echo '<div id="game-name">' . $game->getName() . '</div>';

        if($rounds) {
            $roundNumber = count($rounds);

            echo '<div id="game-round">' . $roundNumber . '/11</div>';
        }

        echo '</div>';
    }

    protected function players(Game $game, $playerId) {
        echo '<div id="players">';

        $players = $game->getPlayers();
        $chosenAnswerPlayerId = null;

        if($round = $game->getCurrentRound()) {
            $chosenAnswerPlayerId = $round->getChosenAnswerPlayerId();
        }

        $notWaiting = false;
        
        if($playerId) {
            $notWaiting = $game->getPlayer($playerId)->getMustWaitForNextRound() === false;
        }

        foreach($players as $player) {
            $me = '';
            $judge = '';
            $winner = '';

            if($player->getId() == $playerId) {
                $me = 'me';
            }

            if($game->isJudge($player->getId())) {
                $judge = 'judge';
            }

            // Only show the winner of a round if the player is a part of the game
            if($playerId !== null && $notWaiting && $player->getId() == $chosenAnswerPlayerId) {
                $winner = 'winner';
            }

            echo '<div class="player ' . $me . ' ' . $judge . ' ' . $winner . '">';

            echo '<div class="token ' . $player->getToken() . '"></div>';
            echo '<div class="name">' . $player->getName() . '</div>';

            echo '</div>';
        }

        echo '</div>';
    }

    protected function countdown($game, $secondsGiven) {
        $secondsElapsed = $game->secondsSinceLastUpdate();

        $secondsLeft = max($secondsGiven - $secondsElapsed, 0);

        echo '<div id="countdown-timer">' .$secondsLeft . '</div>';
    }

    protected function selectOMatic($game, $answers, $chosenAnswerId, $disabled = false) {
        $tokensOfPlayersWhoAnswered = [];
        $spaces = [];
        $chosenAnswerPlayerToken = '';

        foreach($answers as $answer) {
            $player = $game->getPlayer($answer->getPlayerId());

            $token = $player->getToken();

            $checked = '';

            if($answer->getId() == $chosenAnswerId) {
                $chosenAnswerPlayerToken = $token;

                if(!$disabled) {
                    $checked = 'checked';
                }
            }

            $tokensOfPlayersWhoAnswered[] = $token;

            $spaces[] = '<input ' . (($disabled) ? 'disabled' : '') . ' ' . $checked . ' onclick="$(\'#select-o-matic\').attr(\'class\', \'' . $token . '\');" name="answerId" id="' . $token . '" type="radio" value="' . $answer->getId() . '"><label for="' . $token . '" class="space ' . $token . '"></label>';
        }

        $tokens = array_diff(Player::getTokens(), $tokensOfPlayersWhoAnswered);

        foreach($tokens as $token) {
            $spaces[] = '<input disabled name="answerId" id="' . $token . '" type="radio"><label for="' . $token . '" class="inactive space ' . $token . '"></label>';
        }

        echo '<div id="select-o-matic" class="' . $chosenAnswerPlayerToken . '">';

        foreach($spaces as $space) {
            echo $space;
        }

        echo '<div class="arrow"></div>';
        echo "</div>";
    }
}
