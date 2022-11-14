<?php
namespace Views;

use Models\Game;

class GamePartialView implements View {
    private Game $game;
    private $playerId;

    public function __construct(Game $game, $playerId = null) {
        $this->game = $game;
        $this->playerId = $playerId;

        $game->setState(Game::VOTING);
        $this->playerId = 60;
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
           $state !== Game::VOTING &&
           $state !== Game::RESULTS) {
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
            case Game::RESULTS:
                $secondsGiven = Game::SECONDS_UNTIL_NEW_ROUND;
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
                $gameState = "In " . $judge->getName() . "'s opinion ...<br>$question";
            }
        } else if($state === Game::VOTING) {
            $question = $this->game->getCurrentRound()->getAskedQuestion();

            if($this->game->isJudge($this->playerId)) {
                $gameState = "What's your favorite answer to ...<br>$question";
            } else {
                $gameState = "Which answer do you think " . $judge->getName() . " picked for  ...<br>$question";
            }
        } else if($state === Game::RESULTS) {
            $gameState = "Waiting for the next round to begin ...";
        }

        echo "<div id=\"game-state\">$gameState</div>";
    }

    private function playArea() {
        $state = $this->game->getState();
        $gameId = $this->game->getId();
        $isJudge = $this->game->isJudge($this->playerId);

        if($this->playerId === null && $this->game->getNumberOfPlayers() < Game::MAX_PLAYERS) {
            echo '<div class="middle"></div>';
            echo '<div class="bottom">';
            echo '<button type="button" onclick="showModal(\'join-game\');">Join Game</button>';
            echo '</div>';
        } else if($state === Game::WAITING_FOR_PLAYERS) {
            if($this->game->getNumberOfPlayers() >= Game::MIN_PLAYERS &&
                $this->game->isCreator($this->playerId)) {
                echo '<div class="middle"></div>';
                echo '<div class="bottom">';
                echo '<form action="' . $gameId . '/start" method="post">';
                echo '<button type="submit">Start Game</button>';
                echo '</form>';
                echo '</div>';
            }
        } else if($state === Game::ASKING_QUESTION) {
            if($isJudge) {
                $card = $this->game->getCurrentRound()->getCard();

                $questions = $card->getQuestions();

                echo '<div class="middle"></div>';
                echo '<div class="bottom">';
                echo '<form onchange="$(this).find(\'button\').removeAttr(\'disabled\');" action="' . $gameId . '/ask" method="post">';
                echo '<div data-dont-refresh="true" id="questions">';

                foreach($questions as $question) {
                    echo '<label><input name="questionId" type="radio" value="' . $question->getId() . '"><div class="question">' . $question->getQuestion(). '</div></label>';
                }

                echo "</div>";
                echo '<button disabled type="submit">Ask</button>';
                echo "</form>";
                echo '</div>';
            }
        } else if($state === Game::ANSWERING_QUESTION) {
            if(!$isJudge) {
                $answer = $this->game->getCurrentRound()->getPlayerAnswer($this->playerId);

                $readonly = ($answer) ? "readonly" : "";
                $disabled = ($answer) ? "" : "disabled";

                echo '<div class="middle"></div>';
                echo '<div class="bottom">';
                echo '<form data-dont-refresh="true" action="' . $gameId . '/answer" method="post">';
                echo '<textarea oninput="$(this).next(\'button\').prop(\'disabled\', $(this).val().length === 0);" ' . $readonly . ' id="answer" name="answer" onclick="$(this).removeAttr(\'readonly\');">' . $answer . '</textarea>';
                echo '<button ' . $disabled . ' type="submit">Answer</button>';
                echo "</form>";
                echo '</div>';
            }
        } else if($state === Game::VOTING) {
            $answers = $this->game->getCurrentRound()->getAnswers();

            if($isJudge) {
                echo '<div class="middle">';
                echo '<div id="select-o-matic">';
                echo '<input name="player" id="clapperboard" type="radio" value="clapperboard"><label for="clapperboard" class="space clapperboard inactive"></label>';
                echo '<input name="player" id="football" type="radio" value="football"><label for="football" class="space football"></label>';
                echo '<input name="player" id="guitar" type="radio" value="guitar"><label for="guitar" class="space guitar"></label>';
                echo '<input name="player" id="computer" type="radio" value="computer"><label for="computer" class="space computer"></label>';
                echo '<input name="player" id="martini-glass" type="radio" value="martini-glass"><label for="martini-glass" class="space martini-glass"></label>';
                echo '<input name="player" id="dollar-sign" type="radio" value="dollar-sign"><label for="dollar-sign" class="space dollar-sign"></label>';
                echo '<input name="player" id="car" type="radio" value="car"><label for="car" class="space car"></label>';
                echo '<input name="player" id="high-heels" type="radio" value="high-heels"><label for="high-heels" class="space high-heels"></label>';
                echo '<div class="arrow car"></div>';
                echo "</div>";
                echo "</div>";
                echo '<div class="bottom">';
                echo "</div>";
            } else {
                $votes = $this->game->getCurrentRound()->getPlayerVotes($this->playerId);

                echo '<div class="middle">';
                echo '<form onchange="if($(this).find(\'input[type=radio]:checked\').length === 2) { $(this).submit(); }" data-dont-refresh="true" id="vote" action="' . $gameId . '/vote" method="post">';
                echo '<div id="answers">';

                foreach($answers as $answer) {
                    echo '<div class="answer">';
                    echo '<div class="votes">';
                    echo "<label><input ";

                    if($votes && $votes[0]->getAnswerId() === $answer->getId()) {
                        echo "checked ";
                    }

                    echo 'name="vote1" type="radio" value="' . $answer->getId() . '"><div class="token guitar"></div></label>';
                    echo "<label><input ";

                    if($votes && $votes[1]->getAnswerId() === $answer->getId()) {
                        echo "checked ";
                    }
                    
                    echo 'name="vote2" type="radio" value="' . $answer->getId() . '"><div class="token guitar"></div></label>';
                    echo "</div>";
                    echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
                    echo "</div>";
                }

                echo "</div>";
                echo "</form>";
                echo "</div>";
                echo '<div class="bottom"></div>';
            }
        } else if($state === Game::RESULTS) {
            if($this->game->isCreator($this->playerId)) {
                if($this->game->secondsSinceLastUpdate() >= 30) {
                    echo '<form action="' . $gameId . '/nextRound" method="post">';
                    echo '<button type="submit">Next Round</button>';
                    echo '</form>';
                }
            }
        }
    }

    private function joinGame() {
        $gameId = $this->game->getId();

        echo <<<EOD
<div data-dont-refresh="true" id="join-game" class="modal-overlay">
    <div class="modal">
        <form action="$gameId/join" method="post">
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
        echo '<div id="game">';
        echo '<div class="top">';

        $this->gameNameRound();

        $this->players();
    
        $this->gameState();

        $this->countdownTimer();

        echo "</div>";

        $this->playArea();

        $this->joinGame();
    }
}
