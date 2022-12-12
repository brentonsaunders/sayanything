<?php
namespace Models;

use Models\Answer;
use Models\Card;
use Models\Question;
use Models\Vote;

class Round {
    private $id = null;
    private $gameId = null;
    private $roundNumber = null;
    private $judgeId = null;
    private $cardId = null;
    private $questionId = null;
    private $chosenAnswerId = null;
    
    private $card = null;
    private $answers = null;
    private $votes = null;

    public function __construct() {}

    public function getId() {
        return $this->id;
    }

    public function getGameId() {
        return $this->gameId;
    }

    public function getJudgeId() {
        return $this->judgeId;
    }

    public function getCardId() {
        return $this->cardId;
    }

    public function getQuestionId() {
        return $this->questionId;
    }

    public function getChosenAnswerId() {
        return $this->chosenAnswerId;
    }

    public function getCard() {
        return $this->card;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function getVotes() {
        return $this->votes;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setGameId($gameId) {
        $this->gameId = $gameId;
    }

    public function setJudgeId($judgeId) {
        $this->judgeId = $judgeId;
    }

    public function setCardId($cardId) {
        $this->cardId = $cardId;
    }

    public function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    public function setChosenAnswerId($chosenAnswerId) {
        $this->chosenAnswerId = $chosenAnswerId;
    }

    public function getRoundNumber() {
        return $this->roundNumber;
    }

    public function setRoundNumber($roundNumber) {
        $this->roundNumber = $roundNumber;
    }

    public function setCard($card) {
        $this->card = $card;
    }

    public function setAnswers($answers) {
        $this->answers = $answers;
    }

    public function setVotes($votes) {
        $this->votes = $votes;
    }

    public function addAnswer($answer) {
        if(!$this->answers) {
            $this->answers = [];
        }

        $playerId = $answer->getPlayerId();

        if(isset($this->answers[$playerId])) {
            $this->answers[$playerId]->setAnswer($answer->getAnswer());
        } else {
            $this->answers[$answer->getPlayerId()] = $answer;
        }
    }

    public function getPlayerAnswer($playerId) {
        if(!$this->answers) {
            return null;
        }

        return $this->answers[$playerId] ?? null;
    }

    public function getVotesFromPlayer($playerId) {
        if(!$this->votes) {
            return null;
        }

        $playerVotes = [];

        foreach($this->votes as $vote) {
            if($vote->getPlayerId() == $playerId) {
                $playerVotes[] = $vote;
            }
        }

        return $playerVotes;
    }

    public function vote($vote) {
        if(!$this->votes) {
            $this->votes = [];
        }

        $playerId = $vote->getPlayerId();

        if(isset($this->votes[$playerId])) {
            $this->votes[$playerId]->setAnswer1Id($vote->getAnswer1Id());
            $this->votes[$playerId]->setAnswer2Id($vote->getAnswer2Id());
        } else {
            $this->votes[$playerId] = $vote;
        }
    }

    public function hasQuestion($questionId) {
        if(!$this->card) {
            return false;
        }

        $questions = $this->card->getQuestions();

        if(!$questions) {
            return false;
        }

        foreach($questions as $question) {
            if($question->getId() === $questionId) {
                return true;
            }
        }

        return false;
    }

    public function hasAnswer($answerId) {
        if(!$this->answers) {
            return false;
        }

        foreach($this->answers as $answer) {
            if($answer->getId() === $answerId) {
                return true;
            }
        }

        return false;
    }

    public function askRandomQuestion() {
        if($this->questionId) {
            return;
        }

        $questions = $this->card->getQuestions();

        if(!$questions) {
            return null;
        }

        shuffle($questions);

        $this->questionId = $questions[0]->getId();
    }

    public function chooseAnswer($answerId) {
        $this->chosenAnswerId = $answerId;
    }

    public function isJudge($playerId) {
        return $playerId === $this->judgeId;
    }

    public function wroteChosenAnswer($playerId) {
        $chosenAnswerPlayerId = $this->getChosenAnswerPlayerId();
        
        if(!$chosenAnswerPlayerId) {
            return false;
        }

        return $playerId == $chosenAnswerPlayerId;
    }

    public function getChosenAnswerPlayerId() {
        if(!$this->answers) {
            return null;
        }

        foreach($this->answers as $answer) {
            if($answer->getId() === $this->chosenAnswerId) {
                return $answer->getPlayerId();
            }
        }

        return null;
    }

    public function getAskedQuestion() {
        if(!$this->questionId) {
            return null;
        }

        foreach($this->card->getQuestions() as $question) {
            if($question->getId() === $this->questionId) {
                return $question->getQuestion();
            }
        }

        return null;
    }

    public function getVotesForAnswer($answerId) {
        if(!$this->votes) {
            return null;
        }

        $votes = [];

        foreach($this->votes as $vote) {
            if($vote->getAnswerId() === $answerId) {
                $votes[] = $vote;
            }
        }

        return $votes;
    }

    public function getNumVotesForAnswer($answerId) {
        $votesForAnswer = $this->getVotesForAnswer($answerId);

        return $votesForAnswer ? count($votesForAnswer) : 0;
    }

    private function getAnswersAndNumVotesSortedByVotes() {
        if(!$this->answers) {
            return null;
        }

        $answersAndNumVotes = [];

        foreach($this->answers as $answer) {
            $answersAndNumVotes[] = [
                "answer" => $answer,
                "numVotes" => $this->getNumVotesForAnswer($answer->getId())
            ];
        }

        usort($answersAndNumVotes, function($a, $b) {
            return $b["numVotes"] - $a["numVotes"];
        });

        return $answersAndNumVotes;
    }

    public function getAnswersSortedByVotes() {
        $answersAndNumVotes = $this->getAnswersAndNumVotesSortedByVotes();

        if(!$answersAndNumVotes) {
            return null;
        }

        return array_map(function($answer) {
            return $answer["answer"];
        }, $answersAndNumVotes);
    }

    public function getTopAnswers() {
        $answersAndNumVotes = $this->getAnswersAndNumVotesSortedByVotes();

        if(!$answersAndNumVotes) {
            return null;
        }

        $mostVotes = $answersAndNumVotes[0]["numVotes"];

        $topAnswers = [];

        foreach($answersAndNumVotes as $answerAndNumVotes) {
            if($answerAndNumVotes["numVotes"] == $mostVotes) {
                $topAnswers[] = $answerAndNumVotes["answer"];
            }
        }

        return $topAnswers;
    }

    // Returns the ids of the tentative winners of the round
    public function getWinners() {
        if(!$this->answers) {
            return null;
        }

        if($this->chosenAnswerId) {
            return [$this->getChosenAnswerPlayerId()];
        }

        $answers = $this->getTopAnswers();

        $winners = array_map(function($answer) {
            return $answer->getPlayerId();
        }, $answers);

        return  $winners;
    }
}
