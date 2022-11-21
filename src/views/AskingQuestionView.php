<?php
namespace Views;

use Models\Game;

class AskingQuestionView extends GameView {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    public function render() {
        $isJudge = $this->game->isJudge($this->playerId);
        $judge = $this->game->getJudge();

        echo '<div id="game">';
        echo '<div class="top">';

        parent::heading($this->game);

        if(!$isJudge) {
            parent::players($this->game, $this->playerId);
        }

        if($isJudge) {
            echo '<div id="game-state">In My Opinion ...</div>';
        } else {
            echo '<div id="game-state">Waiting for ' . $judge->getName() . ' to ask a question ...</div>';
        }

        parent::countdown($this->game, Game::SECONDS_TO_ASK_QUESTION);

        echo '</div>';
        echo '<div class="middle"></div>';
        echo '<div class="bottom">';

        if($isJudge) {
            $card = $this->game->getCurrentRound()->getCard();

            $questions = $card->getQuestions();

            echo '<form data-dont-refresh="true" id="asking-question" onchange="$(this).find(\'button\').removeAttr(\'disabled\');" action="' . $this->game->getId() . '/ask" method="post">';
            echo '<div id="questions">';

            foreach($questions as $question) {
                echo '<label><input name="questionId" type="radio" value="' . $question->getId() . '"><div class="question">' . $question->getQuestion(). '</div></label>';
            }

            echo "</div>";
            echo '<button disabled type="submit">Ask</button>';
            echo "</form>";
        }


        echo '</div>';
        echo '</div>';
    }
}
