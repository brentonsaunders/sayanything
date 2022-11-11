<?php
namespace Views;

use Models\Game;

class GamePartial implements View {
    private Game $game;
    private $playerId;

    public function __construct(Game $game, $playerId = null) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    private function gameNameRound() {
        $gameName = $this->game->getName();
        $rounds = $this->game->getRounds();

        echo "<div id=\"game-name-round\">";
        echo "<div id=\"game-name\">$gameName</div>";


        if($rounds !== null) {
            $roundNumber = count($rounds);

            echo "<div id=\"round\">$roundNumber/11</div>";
        }

        echo "</div>";
    }

    private function players() {
        $players = $this->game->getPlayers();
        $judge = $this->game->getJudge();

        echo "<div id=\"players\">";

        foreach($players as $player) {
            $meClass = ($this->playerId == $player->getId()) ? "me" : "";

            $judgeClass = ($judge && $judge->getId() == $player->getId()) ? "judge" : "";

            echo "<div class=\"player $meClass $judgeClass\"><div class=\"token {$player->getToken()}\"></div><div class=\"name\">{$player->getName()}</div></div>";
        }

        echo "</div>";
    }

    private function countdownTimer() {
        $gameState = $this->game->getState();

        if($gameState === Game::ASKING_QUESTION ||
           $gameState === Game::ANSWERING_QUESTION ||
           $gameState === Game::VOTING) {
            echo "<div id=\"countdown-timer\">115</div>";
        }
    }

    private function gameState() {
        $gameState = $this->game->getState();

        if($this->playerId === null) {
            return;
        }

        $player = $this->game->getPlayer($this->playerId);

        $creator = $this->game->getCreator();

        if($gameState === Game::WAITING_FOR_PLAYERS) {
            if($this->game->getNumberOfPlayers() < Game::MIN_PLAYERS) {
                echo "<div id=\"game-state\">Waiting for more players ...</div>";
            } else if($this->game->isCreator($this->playerId)) {
                echo "<div id=\"game-state\">Waiting for you to start the game ...</div>";
            } else {
                echo "<div id=\"game-state\">Waiting for " . $creator->getName() . " to start the game ...</div>";
            }
        }
    }

    private function playArea() {
        $gameState = $this->game->getState();

        echo "<div id=\"play-area\">";

        if($gameState === Game::WAITING_FOR_PLAYERS) {
            if($this->playerId === null) {
                echo "<button onclick=\"showModal('create-game');\">Join Game</button>";
            }
            
            if($this->game->getNumberOfPlayers() >= Game::MIN_PLAYERS &&
                $this->game->isCreator($this->playerId)) {
                echo "<button>Start Game</button>";
            }
        }

        echo "</div>";
    }

    public function render() {
        echo "<div id=\"game\">";

        $this->gameNameRound();

        $this->players();
    
        $this->gameState();

        $this->countdownTimer();

        $this->playArea();

        echo "</div>";

        echo <<<EOD
<div data-dont-refresh="true" id="create-game" class="modal-overlay">
    <div class="modal">
        <form>
            <input id="player-name" placeholder="Player Name" type="text">
            <div id="tokens">
                <div class="token martini-glass"></div>
                <div class="token dollar-sign"></div>
                <div class="token high-heels"></div>
                <div class="token computer"></div>
                <div class="token car"></div>
                <div class="token football"></div>
                <div class="token guitar"></div>
                <div class="token clapperboard"></div>
            </div>
            <button>OK</button>
        </form>
    </div>
</div>
EOD;
    }
}