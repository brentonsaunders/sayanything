<?php
namespace Views;

use Models\Game;

class ResultsView extends GameView {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    public function render() {
        $answers = $this->game->getCurrentRound()->getAnswers();
        $chosenAnswerId = $this->game->getCurrentRound()->getChosenAnswerId();
        $winnerId = $this->game->getCurrentRound()->getChosenAnswerPlayerId();
        $winner = $this->game->getPlayer($winnerId);
        $roundNumber = count($this->game->getRounds());

        echo '<div id="game" class="results">';
        echo '<div class="top"></div>';
        echo '<div id="results" data-dont-refresh="true" class="middle">';

        // No answers - judge awarded two points
        // 1 answer - player award two points, player one point
        // No chosen answer - judge loses two points, player with most tokens win
        // Less than two votes - lose a point for each vote not given

        parent::heading($this->game);

        $votes = $this->game->getCurrentRound()->getVotes();

        if(!$answers) {
            $this->noAnswers();
        } else if(count($answers) === 1) {
            $this->onlyOneAnswer($answers);
        } else if(!$chosenAnswerId && !$votes) {
            $this->noChosenAnswerAndNoVotes($answers);
        } else if(!$chosenAnswerId) {
            $this->noChosenAnswer($answers);
        } else {
            parent::selectOMatic($this->game, $answers, $chosenAnswerId, true);

            echo '<div id="game-state">';

            echo $winner->getName() . ' Wins Round ' . $roundNumber . '!<br>';
            
            echo 'Waiting for the next round to begin ...</div>';

            parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);

            parent::players($this->game, $this->playerId);
            
            $this->answers($answers, $chosenAnswerId);
        }
        
        echo '</div>';
        echo '<div class="bottom">';

        if($this->game->isCreator($this->playerId) && $this->game->secondsSinceLastUpdate() >= 30) {
            echo '<form action="' . $this->game->getId() . '/nextRound" method="post">';
            echo '<button type="submit">Next Round</button>';
            echo '</form>';
        }
        
        echo '</div>';
        echo '</div>';
    }

    private function getPlayerWithMostVotes() {
        $answers = $this->game->getCurrentRound()->getAnswers();
        $votes = $this->game->getCurrentRound()->getVotes();

        if(!$answers || !$votes) {
            return null;
        }

        $mostVotes = 0;
        $answersWithMostVotes = [];

        foreach($answers as $answer) {
            $numVotes = 0;

            foreach($votes as $vote) {
                if($vote->getAnswerId() === $answer->getId()) {
                    ++$numVotes;
                }
            }

            if($numVotes === $mostVotes) {
                $answersWithMostVotes[] = $answer;
            } else if($numVotes > $mostVotes) {
                $mostVotes = $numVotes;

                $answersWithMostVotes = [$answer];
            }
        }

        if(count($answersWithMostVotes) === 0) {
            return null;
        }

        $playersWithMostVotes = [];

        foreach($answersWithMostVotes as $answer) {
            $playersWithMostVotes[] = $this->game->getPlayer($answer->getPlayerId())->getName();
        }

        return $playersWithMostVotes;
    }

    private function noAnswers() {
        parent::players($this->game, $this->playerId);

        $judge = $this->game->getJudge();

        echo '<div id="game-state">No player answered the question in time.<br>';
        
        echo $judge->getName() . ' is awarded two points.<br>';
        
        echo 'Waiting for the next round to begin ...</div>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);
    }

    private function onlyOneAnswer($answers) {
        parent::players($this->game, $this->playerId);

        $judge = $this->game->getJudge();

        $answer = $answers[0];

        $player = $this->game->getPlayer($answer->getPlayerId());

        echo '<div id="game-state">Only ' . $player->getName() . ' answered the question in time.<br>';
        
        echo $player->getName() . ' is awarded two points.<br>';

        echo $judge->getName() . ' is awarded one point.<br>';
        
        echo 'Waiting for the next round to begin ...</div>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);

        $this->answers($answers, null);
    }

    private function noChosenAnswerAndNoVotes($answers) {
        parent::players($this->game, $this->playerId);

        $judge = $this->game->getJudge();

        echo '<div id="game-state">' . $judge->getName() . ' didn\'t choose a favorite answer in time, and no one placed a vote.<br>';
        echo 'No points awarded this round.<br>';
        echo 'Waiting for the next round to begin ...</div>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);
        
        $this->answers($answers, null);
    }

    private function noChosenAnswer($answers) {
        parent::players($this->game, $this->playerId);

        $judge = $this->game->getJudge();

        echo '<div id="game-state">' . $judge->getName() . ' didn\'t choose a favorite answer in time.<br>';
       
        $winners = $this->getPlayerWithMostVotes();

        echo implode(" and ", $winners) . " are tied for most votes.<br>";
       
        echo 'Waiting for the next round to begin ...</div>';

        echo '<pre>';

        

        echo '</pre>';

        parent::countdown($this->game, Game::SECONDS_UNTIL_NEW_ROUND);
        
        $this->answers($answers, null);
    }

    private function answers($answers, $chosenAnswerId) {
        $votes = $this->game->getCurrentRound()->getVotes();

        $votesForAnswer = function($answerId) use($votes) {
            if(!$votes) {
                return [];
            }

            return array_filter($votes, function($vote) use($answerId) {
                return $vote->getAnswerId() == $answerId;
            });
        };

        // Bring the chosen answer to the top, and then sort the rest by number
        // of votes
        usort($answers, function($a, $b) use($chosenAnswerId, $votesForAnswer) {
            if($a->getId() == $chosenAnswerId) {
                return -1;
            } else if($b->getId() == $chosenAnswerId) {
                return 1;
            }

            $numVotesForA = count($votesForAnswer($a->getId()));
            $numVotesForB = count($votesForAnswer($b->getId()));

            return $numVotesForB - $numVotesForA;
        });

        echo '<div class="results" id="answers">';

        foreach($answers as $answer) {
            $player = $this->game->getPlayer($answer->getPlayerId());

            echo '<div class="answer ' . $player->getToken() . '">';
            echo '<div class="votes">';

            if($votes) {
                foreach($votes as $vote) {
                    if($vote->getAnswerId() == $answer->getId()) {
                        $player = $this->game->getPlayer($vote->getPlayerId());

                        echo '<div class="token ' . $player->getToken() . '"></div>';
                    }
                }
            }

            echo '</div>';
            echo '<div class="answer-text">' . $answer->getAnswer() . '</div>';
            echo "</div>";
        }

        echo "</div>";
    }
}
