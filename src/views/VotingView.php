<?php
namespace Views;

use Models\Game;

class VotingView extends GameView {
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

        $question = $this->game->getCurrentRound()->getAskedQuestion();

        if($isJudge) {
            echo '<div id="game-state">What\'s your favorite answer to ...<br>' . $question . '</div>';
        } else {
            echo '<div id="game-state">Which answer do you think ' . $judge->getName() . ' picked for  ...<br>' . $question . '</div>';
        }

        parent::countdown($this->game, Game::SECONDS_TO_VOTE);

        echo '</div>';
        echo '<div id="choose-answer" data-dont-refresh="true" class="middle">';

        $answers = $this->game->getCurrentRound()->getAnswers();
        $chosenAnswerId = $this->game->getCurrentRound()->getChosenAnswerId();

        if($isJudge) {
            echo '<form onchange="$(this).submit();" action="' . $this->game->getId() . '/chooseAnswer" method="post">';

            $this->selectOMatic($this->game, $answers, $chosenAnswerId);
            $this->answersForJudge($answers);

            echo '</form>';
        } else {
            $this->answersForPlayers($answers);
        }

        echo '</div>';
        echo '<div class="bottom"></div>';
        echo '</div>';
    }

    private function answersForJudge($answers) {
        echo '<div class="choosing-answer" id="answers">';

        foreach($answers as $answer) {
            $token = $this->game->getPlayer($answer->getPlayerId())->getToken();

            echo '<div class="answer ' . $token. '">';
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
    }

    private function answersForPlayers($answers) {
        $votes = $this->game->getCurrentRound()->getPlayerVotes($this->playerId);
        $token = $this->game->getPlayer($this->playerId)->getToken();

        echo '<form onchange="if($(this).find(\'input[type=radio]:checked\').length === 2) { $(this).submit(); }" data-dont-refresh="true" id="vote" action="' . $this->game->getId() . '/vote" method="post">';
        echo '<div class="voting" id="answers">';

        foreach($answers as $answer) {
            echo '<div class="answer">';
            echo '<div class="votes">';
            echo "<label><input ";

            if($votes && $votes[0]->getAnswerId() === $answer->getId()) {
                echo "checked ";
            }

            echo 'name="vote1" type="radio" value="' . $answer->getId() . '"><div class="token ' . $token . '"></div></label>';
            echo "<label><input ";

            if($votes && $votes[1]->getAnswerId() === $answer->getId()) {
                echo "checked ";
            }
            
            echo 'name="vote2" type="radio" value="' . $answer->getId() . '"><div class="token ' . $token . '"></div></label>';
            echo "</div>";
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
        echo "</form>";
    }
}
