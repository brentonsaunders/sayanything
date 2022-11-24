<?php
namespace Views;

use Models\Game;

class VotingView extends GameView {
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    protected function head() {
        $isJudge = $this->game->isJudge($this->playerId);
        $judgeName = $this->game->getJudge()->getName();
        $question = $this->game->getCurrentRound()->getAskedQuestion();

        parent::heading();

        parent::countdownTimer(Game::SECONDS_TO_VOTE);

        if($isJudge) {
            parent::gameState("Choose your favorite answer to ...<br>$question");
        } else {
            parent::gameState("Vote on what you think is $judgeName's favorite answer to ...<br>$question");
        }
    }

    protected function body() {
        $isJudge = $this->game->isJudge($this->playerId);

        if($isJudge) {
            $answers = $this->game->getCurrentRound()->getAnswers();
            $chosenAnswerId = $this->game->getCurrentRound()->getChosenAnswerId();

            $this->selectOMatic($answers, $chosenAnswerId);

            $this->answerCardsForJudge();
        } else {
            $this->answerCardsForPlayers();
        }
    }

    private function answerCardsForJudge() {
        $round = $this->game->getCurrentRound();
        $answers = $round->getAnswers();
        
        echo '<div id="answer-cards">';

        foreach($answers as $answer) {
            $this->answerCard($answer);
        }

        echo '</div>';
    }

    private function answerCardsForPlayers() {
        $round = $this->game->getCurrentRound();
        $answers = $round->getAnswers();
        $votes = $round->getVotesFromPlayer($this->playerId);
        $token = $this->game->getPlayer($this->playerId)->getToken();
        
        echo '<form data-dont-refresh="true" id="voting" onchange="$(this).find(\'input[type=radio]:checked\').length === 2 && $(this).submit();" action="' . $this->game->getId() . '/vote" method="post">';
        echo '<div id="answer-cards">';

        foreach($answers as $answer) {
            $this->answerCardWithVotes($token, $answer, $votes);
        }

        echo '</div>';
        echo '</form>';
    }

    protected function answerCardWithVotes($token, $answer, $votesFromPlayer) {
        if(!$answer) {
            return;
        }

        echo '<div class="answer-card voting">';
        echo '<div class="votes top">';

        for($i = 0; $i < 2; ++$i) {
            $checked = "";

            if($votesFromPlayer && $votesFromPlayer[$i]->getAnswerId() === $answer->getId()) {
                $checked = "checked";
            }

            echo '<label><input ' . $checked . ' name="vote'. ($i + 1) . '" type="radio" value="' . $answer->getId() . '"><div class="token ' . $token . '"></div></label>';
        }

        echo '</div>';
        echo '<div class="answer-card-text">';

        echo $answer->getAnswer();

        echo '</div>';
        echo '</div>';
    }

    protected function answerCard($answer) {
        if(!$answer) {
            return;
        }

        $token = $this->game->getPlayer($answer->getPlayerId())->getToken();

        echo '<div class="answer-card ' . $token . '">';
        echo '<div class="answer-card-text">';

        echo $answer->getAnswer();

        echo '</div>';
        echo '</div>';
    }
}
