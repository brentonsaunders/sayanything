<?php
namespace Views;

use Models\Game;

class GamePartialView implements View {
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
        $state = $this->game->getState();

        if($this->playerId) {
            if($state === Game::ASKING_QUESTION &&  $this->game->isJudge($this->playerId)) {
                return;
            }

            if($state === Game::VOTING) {
                return;
            }
        }

        $players = $this->game->getPlayers();
        $judge = $this->game->getJudge();

        usort($players, function($a, $b) {
            if($a->getTurn() < $b->getTurn()) {
                return -1;
            } else if($a->getTurn() > $b->getTurn()) {
                return 1;
            }

            return 0;
        });

        echo "<div id=\"players\">";

        foreach($players as $player) {
            $meClass = ($this->playerId == $player->getId()) ? "me" : "";

            $judgeClass = ($judge && $judge->getId() == $player->getId()) ? "judge" : "";

            echo "<div class=\"player $meClass $judgeClass\"><div class=\"token {$player->getToken()}\"></div><div class=\"name\">{$player->getName()}</div></div>";
        }

        echo "</div>";
    }

    private function countdownTimer() {
        if(!$this->playerId) {
            return;
        }

        $state = $this->game->getState();

        if($state !== Game::ASKING_QUESTION &&
           $state !== Game::ANSWERING_QUESTION &&
           $state !== Game::VOTING) {
            return;
        }

        $secondsElapsed = $this->game->secondsSinceLastUpdate();
        $secondsGiven = 0;

        switch($state) {
            case Game::ASKING_QUESTION:
                $secondsGiven = Game::SECONDS_TO_ASK_QUESTION;
                break;
            case Game::ANSWERING_QUESTION:
                $secondsGiven = Game::SECONDS_TO_ANSWER_QUESTION;
                break;
            case Game::VOTING:
                $secondsGiven = Game::SECONDS_TO_VOTE;
                break;
        }

        $secondsLeft = $secondsGiven - $secondsElapsed;

        echo "<div id=\"countdown-timer\">$secondsLeft</div>";
    }

    private function gameState() {
        $state = $this->game->getState();

        if($this->playerId === null) {
            return;
        }

        $player = $this->game->getPlayer($this->playerId);
        $creator = $this->game->getCreator();
        $judge = $this->game->getJudge();

        $gameState = "";

        if($state === Game::WAITING_FOR_PLAYERS) {
            if($this->game->getNumberOfPlayers() < Game::MIN_PLAYERS) {
                $gameState = "Waiting for more players ...";
            } else if($this->game->isCreator($this->playerId)) {
                $gameState = "Waiting for you to start the game ...";
            } else {
                $gameState = "Waiting for " . $creator->getName() . " to start the game ...";
            }
        } else if($state === Game::ASKING_QUESTION) {
            if($this->game->isJudge($this->playerId)) {
                $gameState = "In My Opinion ...";
            } else {
                $gameState = "Waiting for " . $judge->getName() . " to ask a question ...";
            }
        } else if($state === Game::ANSWERING_QUESTION) {
            $question = $this->game->getCurrentRound()->getAskedQuestion();

            if($this->game->isJudge($this->playerId)) {
                $gameState = "Waiting for players to answer the question<br>$question ...";
            } else {
                $gameState = "In " . $judge->getName() . "'s Opinion ...<br>$question";
            }
        } else if($state === Game::VOTING) {
            $question = $this->game->getCurrentRound()->getAskedQuestion();

            if($this->game->isJudge($this->playerId)) {
                $gameState = "What's the best answer to ...<br>$question";
            } else {
                $gameState = "Vote on the best answer to ...<br>$question";
            }
        }

        echo "<div id=\"game-state\">$gameState</div>";
    }

    private function playArea() {
        

        $state = $this->game->getState();
        $gameId = $this->game->getId();

        echo "<div id=\"play-area\">";

        if($this->playerId === null && $this->game->getNumberOfPlayers() < Game::MAX_PLAYERS) {
            echo "<button onclick=\"showModal('join-game');\">Join Game</button>";
        }
        else if($state === Game::WAITING_FOR_PLAYERS) {
            if($this->game->getNumberOfPlayers() >= Game::MIN_PLAYERS &&
                $this->game->isCreator($this->playerId)) {
                echo "<form action=\"game/start\">";
                echo "<input type=\"hidden\" name=\"gameId\" value=\"$gameId\">";
                echo "<button type=\"submit\">Start Game</button>";
                echo "</form>";
            }
        } else if($state === Game::ASKING_QUESTION) {
            if($this->game->isJudge($this->playerId)) {
                $card = $this->game->getCurrentRound()->getCard();

                $questions = $card->getQuestions();

                echo "<form action=\"game/ask\">";
                echo "<input type=\"hidden\" name=\"gameId\" value=\"$gameId\">";
                echo "<div data-dont-refresh=\"true\" id=\"questions\">";

                foreach($questions as $question) {
                    echo "<label><input name=\"questionId\" type=\"radio\" value=\"{$question->getId()}\"><div class=\"question\">{$question->getQuestion()}</div></label>";
                }

                echo "</div>";
                echo "<button type=\"submit\">Ask</button>";
                echo "</form>";
            }
        } else if($state === Game::ANSWERING_QUESTION) {
            if(!$this->game->isJudge($this->playerId)) {
                $answer = $this->game->getCurrentRound()->getPlayerAnswer($this->playerId);

                $disabled = ($answer) ? "disabled" : "";

                echo "<form data-dont-refresh=\"true\" id=\"answer-question\" action=\"game/answer\">";
                echo "<input type=\"hidden\" name=\"gameId\" value=\"$gameId\">";
                echo "<textarea $disabled id=\"answer\" name=\"answer\">$answer</textarea>";
                
                if($answer) {
                    echo "<button onclick=\"$(this).hide(); $(this).next().show(); $('#answer').prop('disabled', false);\" type=\"button\">Edit</button>";
                    echo "<button style=\"display: none;\" type=\"submit\">Answer</button>";
                } else {
                    echo "<button type=\"submit\">Answer</button>";
                }

                echo "</form>";
            }
        } else if($state === Game::VOTING) {
            if($this->game->isJudge($this->playerId)) {
            } else {
                $answers = $this->game->getCurrentRound()->getAnswers();

                echo "<form data-dont-refresh=\"true\" id=\"vote\" action=\"game/vote\">";
                echo "<input type=\"hidden\" name=\"gameId\" value=\"$gameId\">";
                echo "<div id=\"picked-answer\"></div>";
                echo "<div id=\"answer-picker\">";

                for($i = 0; $i < count($answers); ++$i) {
                    echo "<div class=\"answer-picker-column\"><label><input name=\"picked-answer\" type=\"radio\"><span>$i</span></label></div>";
                }

                echo "</div>";
                echo "<button type=\"submit\">Vote</button>";
                echo "</form>";
            }
        } 

        echo "</div>";
    }

    private function joinGame() {
        $gameId = $this->game->getId();

        echo <<<EOD
<div data-dont-refresh="true" id="join-game" class="modal-overlay">
    <div class="modal">
        <form action="game/join" method="get">
            <input type="hidden" name="gameId" value="$gameId">
            <h2>Join Game</h2>
            <input id="player-name" name="playerName" placeholder="Your Name" type="text">
            <h2>Your Token</h2>
            <div id="tokens">
EOD;

        $tokens = $this->game->getAvailableTokens();

        foreach($tokens as $token) {
            echo "<label><input type=\"radio\" name=\"playerToken\" value=\"$token\"><div class=\"token $token\"></div></label>";
        }
        
        echo <<<EOD
            </div>
            <button type="submit">OK</button>
        </form>
    </div>
</div>
EOD;
    }

    public function render() {
        echo "<div id=\"game\">";

        $this->gameNameRound();

        $this->players();
    
        $this->gameState();

        $this->countdownTimer();

        $this->playArea();

        echo "</div>";

        $this->joinGame();
    }
}
