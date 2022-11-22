<?php
namespace Views;

use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Round;
use Models\Vote;

class TestGameView{
    private Game $game;

    public function __construct() {
        $this->game = new Game(
            1,
            "Redbud Ballers",
            1,
            Game::WAITING_FOR_PLAYERS,
            date( 'Y-m-d H:i:s', time()),
            date( 'Y-m-d H:i:s', time())
        );

        $this->game->setPlayers([
            new Player(1, 1, "Brenton", Player::FOOTBALL, 0, 0, 0),
            new Player(2, 1, "Prem", Player::MARTINI_GLASS, 1, 0, 0),
            new Player(3, 1, "TJ", Player::CAR, 2, 0, 0),
            new Player(4, 1, "Bablu", Player::DOLLAR_SIGN, 3, 0, 0),
            new Player(5, 1, "Saad", Player::CLAPPERBOARD, 4, 0, 0),
        ]);

        $round = new Round(1, 1, 1, 1, 1, null);

        $round->setCard(new Card(1, [
            new Question(1, 1, "Which technology product would be the hardest to live without?"),
            new Question(2, 1, "What would be the most dangerous stunt for a movie stuntman?"),
            new Question(3, 1, "If I could train a monkey to do anything, what would it be?"),
        ]));

        $round->setAnswers([
            new Answer(1, 2, 1, "Instagram"),
            new Answer(2, 3, 1, "PS5"),
            new Answer(3, 4, 1, "Internal combustion engine"),
            new Answer(4, 5, 1, "X-ray imaging")
        ]);

        $round->setVotes([
            new Vote(1, 1, 2, 2), new Vote(1, 1, 2, 3),
            new Vote(1, 1, 3, 2), new Vote(1, 1, 3, 2),
            new Vote(1, 1, 4, 3), new Vote(1, 1, 4, 3),
            new Vote(1, 1, 5, 1), new Vote(1, 1, 5, 3),
        ]);

        $this->game->setRounds([$round]);
    }

    public function render() {
        echo '<div id="game">';
        echo '<div class="middle">';

        $round = $this->game->getCurrentRound();
        $answers = $round->getAnswersSortedByVotes();
        $votes = $round->getVotes();

        // echo '<div id="select-o-matic" class="martini-glass"></div>';

        echo '<div id="answer-boards">';
        
        if($answers) {
            foreach($answers as $answer) {
                $this->answerBoard(
                    $this->game,
                    $answer,
                    $round->getVotesForAnswer($answer->getId()),
                    false
                );
            }
        }

        echo '</div>';

        echo '</div>';
        echo '</div>';
    }

    protected function vote($game, $vote) {
        if(!$vote) {
            return;
        }

        $token = $game->getPlayer($vote->getPlayerId())->getToken();

        echo '<div class="token ' . $token . '"></div>';
    }

    protected function votes($game, $votes) {
        if(!$votes) {
            return;
        }

        $votesTop = array_slice($votes, 0, 8);
        $votesBottom = array_slice($votes, 8);

        echo '<div class="votes top">';

        foreach($votesTop as $vote) {
            $this->vote($game, $vote);
        }

        echo '</div>';

        echo '<div class="votes bottom">';

        foreach($votesBottom as $vote) {
            $this->vote($game, $vote);
        }

        echo '</div>';
    }

    protected function blankAnswerBoard($playerToken) {
        echo '<div class="answer-board ' . $playerToken . '">'; 
        echo '<div class="answer-board-text">';
        echo '<textarea name="answer"></textarea>';
        echo '</div>';
        echo '</div>';
    }

    protected function votingAnswerBoard($playerToken, $answer, $votesFromPlayer) {
        if(!$answer) {
            return;
        }

        echo '<div class="answer-board voting">';
        echo '<div class="votes top">';

        for($i = 0; $i < 2; ++$i) {
            $checked = "";

            if($votesFromPlayer && $votesFromPlayer[$i]->getAnswerId() === $answer->getId()) {
                $checked = "checked";
            }

            echo '<label><input ' . $checked . ' name="vote'. ($i + 1) . '" type="radio"><div class="token ' . $playerToken . '"></div></label>';
        }

        echo '</div>';
        echo '<div class="answer-board-text">';

        echo $answer->getAnswer();

        echo '</div>';
        echo '</div>';
    }

    protected function answerBoard($game, $answer, $votes, $isWritable=true) {
        if(!$game || !$answer) {
            return;
        }

        $token = $game->getPlayer($answer->getPlayerId())->getToken();

        echo '<div class="answer-board ' . $token . '">';

        $this->votes($game, $votes);

        echo '<div class="answer-board-text">';
        
        if($isWritable) {
            echo '<textarea readonly name="answer" onclick="$(this).attr(\'readonly\', false);">';
            echo $answer->getAnswer();
            echo '</textarea>';
        } else {
            echo $answer->getAnswer();
        }

        echo '</div>';
        echo '</div>';
    }
}