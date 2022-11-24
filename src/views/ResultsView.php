<?php
namespace Views;

use Models\Game;

class ResultsView extends GameView {
    private $playerId = null;
    private $round = null;
    private $isCurrentRound = false;

    public function __construct(Game $game, $playerId, $round = null) {
        parent::__construct($game);

        $this->playerId = $playerId;

        if($round) {
            $this->round = $round;
        } else {
            $this->round = $game->getCurrentRound();
        }

        if($this->round->getId() == $game->getCurrentRound()->getId()) {
            $this->isCurrentRound = true;
        }
    }

    protected function head() {
        parent::heading($this->round->getId());

        if($this->isCurrentRound && !$this->game->isOver()) {
            parent::countdownTimer(Game::SECONDS_UNTIL_NEW_ROUND);
        }
    }

    protected function body() {
        $answers = $this->round->getAnswersSortedByVotes();
        $chosenAnswerId = $this->round->getChosenAnswerId();
        $judgeName = $this->game->getJudge()->getName();
        $roundNumber = $this->game->getRoundNumber($this->round->getId());
        $winners = $this->round->getWinners();
        $question = $this->round->getAskedQuestion();

        $waitingForNextRound = "";

        if($this->isCurrentRound && !$this->game->isOver()) {
            $waitingForNextRound = "Waiting for the next round to begin ...";
        }

        if(!$answers) {
            parent::gameState(
                $question . "<br>" . 
                "No one answered in time!<br>" . 
                $waitingForNextRound
            );
        } else if(count($answers) === 1) {
            $player = $this->game->getPlayer($winners[0]);
            $playerName = $player->getName();

            parent::gameState(
                $question . "<br>" . 
                "$playerName was the only one to answer the question!<br>" . 
                "$playerName wins round $roundNumber!<br>" . 
                $waitingForNextRound
            );
        } else if($chosenAnswerId) {
            $this->selectOMatic($answers, $chosenAnswerId, true);

            $player = $this->game->getPlayer($this->round->getChosenAnswerPlayerId());
            $playerName = $player->getName();

            parent::gameState(
                $question . "<br>" . 
                "$playerName wins round $roundNumber with the chosen answer!<br>" . 
                $waitingForNextRound
            );
        } else {
            $winnerNames = array_map(function($winner) {
                return $this->game->getPlayer($winner)->getName();
            }, $winners);

            parent::gameState(
                $question . "<br>" . 
                "$judgeName didn't choose a favorite answer in time!<br>" .
                implode(" and ", $winnerNames) .
                " wins round $roundNumber with the most votes!<br>" . 
                $waitingForNextRound
            );
        }

        parent::playerTokens($this->playerId, $winners, $this->round->getId());

        $this->answerCards($answers);

        if($this->isCurrentRound) {
            if($this->game->isOver()) {
                $this->scoresButton();
            } else if($this->game->isCreator($this->playerId) &&
                      $this->game->secondsSinceLastUpdate() >= 30) {
                $this->nextRoundButton();
            }
        }
    }

    protected function nextRoundButton() {
        echo '<form action="' . $this->game->getId() . '/nextRound" method="post">';
        echo '<button id="next-round-button" type="submit">Next Round</button>';
        echo '</form>';
    }

    protected function scoresButton() {
        echo '<button onclick="window.location = \'' . $this->game->getId() . '\';" id="scores-button" type="submit">Scores</button>';
    }

    protected function vote($vote) {
        if(!$vote) {
            return;
        }

        $token = $this->game->getPlayer($vote->getPlayerId())->getToken();

        echo '<div class="token ' . $token . '"></div>';
    }

    protected function votes($votes) {
        if(!$votes) {
            return;
        }

        $votesTop = array_slice($votes, 0, 8);
        $votesBottom = array_slice($votes, 8);

        echo '<div class="votes top">';

        foreach($votesTop as $vote) {
            $this->vote($vote);
        }

        echo '</div>';

        echo '<div class="votes bottom">';

        foreach($votesBottom as $vote) {
            $this->vote($game, $vote);
        }

        echo '</div>';
    }


    private function answerCards($answers) {
        if(!$answers) {
            return;
        }

        $round = $this->game->getCurrentRound();
        
        echo '<div id="answer-cards">';

        foreach($answers as $answer) {
            $votes = $this->round->getVotesForAnswer($answer->getId());

            $this->answerCard($answer, $votes);
        }

        echo '</div>';
    }

    protected function answerCard($answer, $votes) {
        if(!$answer) {
            return;
        }

        $token = $this->game->getPlayer($answer->getPlayerId())->getToken();

        echo '<div class="answer-card ' . $token . '">';

        $this->votes($votes);

        echo '<div class="answer-card-text">';
        echo $answer->getAnswer();

        echo '</div>';
        echo '</div>';
    }
}
