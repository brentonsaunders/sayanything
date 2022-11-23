<?php
namespace Views;

use Models\Game;

class AskingQuestionView extends GameView {
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        parent::__construct($game);

        $this->playerId = $playerId;
    }

    protected function body() {
        $isJudge = $this->game->isJudge($this->playerId);
        $judgeName = $this->game->getJudge()->getName();
        $questions = $this->game->getCurrentRound()->getCard()->getQuestions();

        if(!$isJudge) {
            parent::playerTokens($this->playerId);

            parent::gameState("Waiting for $judgeName to ask a question ...");
        } else {
            parent::gameState("In My Opinion ...");

            $this->questionsCard($questions);
        }

        parent::countdownTimer(Game::SECONDS_TO_ASK_QUESTION);
    }

    private function questionsCard($questions) {
        echo '<form data-dont-refresh="true" id="asking-question" onchange="$(this).find(\'button\').removeAttr(\'disabled\');" action="' . $this->game->getId() . '/ask" method="post">';
        echo '<div id="questions-card">';
        echo '<div class="questions">';

        foreach($questions as $question) {
            echo '<label>';
            echo '<input name="question" type="radio">';
            echo '<span>' . $question->getQuestion() . '</span>';
            echo '</label>';
        }

        echo '</div>';
        echo '</div>';
        echo '<button disabled type="submit">Ask</button>';
        echo '</form>';
    }
}
