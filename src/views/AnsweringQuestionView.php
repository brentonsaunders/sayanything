<?php
namespace Views;

use Models\Game;

class AnsweringQuestionView extends GameView {
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

        if($isJudge) {
            parent::players($this->game, $this->playerId);
        }

        $question = $this->game->getCurrentRound()->getAskedQuestion();

        if($isJudge) {
            echo '<div id="game-state">Waiting for players to answer the question ...<br>' . $question . '</div>';
        } else {
            echo '<div id="game-state">In ' . $judge->getName() . '\'s Opinion ...<br>' . $question . '</div>';
        }

        parent::countdown($this->game, Game::SECONDS_TO_ANSWER_QUESTION);

        echo '</div>';
        echo '<div class="middle"></div>';
        echo '<div class="bottom">';

        if(!$isJudge) {
            $answer = $this->game->getCurrentRound()->getPlayerAnswer($this->playerId);

            $readonly = ($answer) ? "readonly" : "";
            $disabled = ($answer) ? "" : "disabled";
            $answerText = ($answer) ? $answer->getAnswer() : "";

            echo '<form id="answering-question" data-dont-refresh="true" action="' . $this->game->getId() . '/answer" method="post">';
            echo '<textarea oninput="$(this).next(\'button\').prop(\'disabled\', $(this).val().length === 0);" ' . $readonly . ' id="answer" name="answer" onclick="$(this).removeAttr(\'readonly\');">' . $answerText . '</textarea>';
            echo '<button ' . $disabled . ' type="submit">Answer</button>';
            echo "</form>";
        }

        echo '</div>';
        echo '</div>';
    }
}
