<?php
namespace Views;

use Models\Game;

class AnsweringQuestionView extends GameView {
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        parent::__construct($game);

        $this->playerId = $playerId;
    }

    protected function head() {
        parent::heading();

        parent::countdownTimer(Game::SECONDS_TO_ANSWER_QUESTION);
    }

    protected function body() {
        $isJudge = $this->game->isJudge($this->playerId);
        $judgeName = $this->game->getJudge()->getName();
        $question = $this->game->getCurrentRound()->getAskedQuestion();

        if($isJudge) {
            parent::playerTokens($this->playerId);

            parent::gameState("Waiting for players to answer ...<br>$question");
        } else {
            parent::gameState("In $judgeName's Opinion ...<br>$question");
        }
        if(!$isJudge) {
            $this->answerCard();
        }
    }

    protected function answerCard() {
        $token = $this->game->getPlayer($this->playerId)->getToken();
        $round = $this->game->getCurrentRound();
        $answer = $round->getPlayerAnswer($this->playerId);

        echo '<form id="answering-question" data-dont-refresh="true" action="' . $this->game->getId() . '/answer" method="post">';
        echo '<div class="answer-card ' . $token . '">';
        echo '<div class="answer-card-text">';

        $readonly = "";
        $disabled = "";

        if($answer) {
            $readonly = "readonly";
            $disabled = "disabled";
        }

        echo '<textarea ' . $readonly . ' id="answer" name="answer" onclick="$(this).attr(\'readonly\', false); $(\'#answer-button\').attr(\'disabled\', false);">';
        

        if($answer) {
            echo $answer->getAnswer();
        }

        echo '</textarea>';
        echo '</div>';
        echo '</div>';
        echo '<button id="answer-button" ' . $disabled . ' type="submit">Answer</button>';
        echo '</form>';
    }
}
