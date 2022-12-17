<?php
namespace Views;

use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Round;
use Models\Vote;

class GameView extends View {
    private $content = "";
    private $gameId = null;

    private function __construct($gameId) {
        $this->gameId = $gameId;
    }

    public static function builder($gameId) {
        return new GameView($gameId);
    }

    public function withSidebar($content = "") {
        $this->content .= '<div id="sidebar">' . 
                          '<header>' . 
                          '<a id="close-button"><div class="close"><span></span><span></span></div></a>' . 
                          '</header>' . 
                          '<main>' . 
                          $content .
                          '</main>' . 
                          '</div>';

        return $this;
    }

    public function withAnswerPicker(Player $judge, Answer ...$answers) {
        $this->content .= '<div id="answer-picker" onclick="showModal(\'#select-o-matic-modal\').then(() => $(\'#choose-answer-form\').submit());">' .
                          '<form id="choose-answer-form" action="' . $this->gameId . '/chooseAnswer" method="post">' . 
                          '<input name="playerId" type="hidden" value="' . $judge->getId() . '">' . 
                          '<input name="chosenAnswerId" type="hidden">' . 
                          '</form>' .
                          '<h2>Tap to pick your favorite answer</h2>' .
                          '<div id="chosen-answer-token" class="token none"></div>' .
                          '<p>No answer chosen yet</p>' .
                          '</div>' .
                          '<div class="modal" id="select-o-matic-modal">';

        $this->withSelectOMatic(...$answers);

        $this->content .= '</div>';  

        return $this;
    }

    public function withSelectOMatic(Answer ...$answers) {
        $this->content .= '<div id="select-o-matic">';

        $tokens = [];

        foreach($answers as $answer) {
            $token = $answer->getPlayer()->getToken();
            $answerId = $answer->getId();

            $tokens[] = $token;

            $this->content .= "<div class=\"bg-$token\" onclick=\"select('$token', $answerId);\"></div>";
        }

        $remainingTokens = array_diff(Player::getTokens(), $tokens);

        foreach($remainingTokens as $token) {
            $this->content .= "<div class=\"bg-$token disabled\"></div>";
        }

        $this->content .= '<div class="arrow"></div>' .
                          '</div>';

        return $this;
    }

    public function withAnswersToBeChosenByJudge($answers) {
        if(!$answers) {
            return $this;
        }

        $this->content .= '<div id="answers">';
                    

        foreach($answers as $index => $answer) {
            $modalId = "answer-$index";

            $class = 'white-text shadow bg-color-' . $answer->getPlayer()->getToken();

            $this->content .= '<div class="answer-short ' . $class . '" onclick="showModal(\'#' . $modalId . '\');">';
            $this->content .= '<div class="text">' . $answer->getAnswer() . '</div>';
            $this->content .= '</div>';

            $this->content .= '<div id="' . $modalId . '" class="modal">' .
                              '<div class="answer ' . $class . '">' .
                              $answer->getAnswer() . 
                              '</div>' .
                              '</div>';
        }

        $this->content .= '</div>';

        return $this;
    }

    public function withAnswers($answers, $votes, $player, $showColors) {
        if(!$answers) {
            return $this;
        }

        $this->content .= '<div id="answers">';
                    

        foreach($answers as $index => $answer) {
            $modalId = "answer-$index";

            $numVotes = 0;

            foreach($votes as $vote) {
                if($vote->getAnswer1Id() === $answer->getId()) {
                    ++$numVotes;
                }

                if($vote->getAnswer2Id() === $answer->getId()) {
                    ++$numVotes;
                }
            }

            if ($showColors) {
                $class = 'white-text shadow bg-color-' . $answer->getPlayer()->getToken();
            } else {
                $class = 'bg-color-white';
            }

            $this->content .= '<div class="answer-short ' . $class . '" onclick="showModal(\'#' . $modalId . '\');">';
            $this->content .= '<div class="text">' . $answer->getAnswer() . '</div>';
            $this->content .= '<div class="num-votes">' . (($numVotes === 0) ? '' : $numVotes) . '</div>';
            $this->content .= '</div>';

            $this->content .= '<div id="' . $modalId . '" class="modal">' .
                              '<div class="answer ' . $class . '">' .
                              '<div class="votes">'; 

            foreach($votes as $vote) {
                $token = $vote->getPlayer()->getToken();

                if($vote->getAnswer1Id() === $answer->getId()) {
                    $this->content .= "<div class=\"token bg-$token\"></div>";
                }

                if($vote->getAnswer2Id() === $answer->getId()) {
                    $this->content .= "<div class=\"token bg-$token\"></div>";
                }
            }

            $this->content .= '</div>' .
                              $answer->getAnswer() . 
                              '</div>' .
                              '</div>';
        }

        $this->content .= '</div>';

        return $this;
    }

    public function withAnswersToBeVotedOn($answers, Player $player, Vote $vote) {
        if(!$answers) {
            return $this;
        }

        $myToken = $player->getToken();

        $this->content .= '<div id="answers">';
        $this->content .= '<form id="vote-form" action="' . $this->gameId . '/vote" method="post">' . 
                          '<input name="playerId" type="hidden" value="' . $player->getId() . '">' .
                          '</form>';
                    

        foreach($answers as $index => $answer) {
            $numVotes = 0;
            $checked1 = "";
            $checked2 = "";

            if($vote->getAnswer1Id() == $answer->getId()) {
                ++$numVotes;

                $checked1 = "checked";
            }

            if($vote->getAnswer2Id() == $answer->getId()) {
                ++$numVotes;

                $checked2 = "checked";
            }

            $modalId = "answer-$index";

            $this->content .= '<div class="answer-short bg-color-white" onclick="showModal(\'#' . $modalId . '\').then(() => $(\'#vote-form\').submit());">';
            $this->content .= '<div class="text">' . $answer->getAnswer() . '</div>';
            $this->content .= '<div class="num-votes">' . (($numVotes === 0) ? '' : $numVotes) . '</div>';
            $this->content .= '</div>';

            $this->content .= '<div id="' . $modalId . '" class="modal">' .
                              '<div class="answer bg-color-white" onclick="vote(\'' . $answer->getId() . '\');">' . 
                              '<div class="votes">' . 
                              '<label><input ' . $checked1 . ' form="vote-form" name="vote1" type="radio" value="' . $answer->getId() . '"><span class="token bg-' . $myToken . '"></span></label>' . 
                              '<label><input ' . $checked2 . ' form="vote-form" name="vote2" type="radio" value="' . $answer->getId() . '"><span class="token bg-' . $myToken . '"></span></label>' . 
                              '</div>' . 
                              $answer->getAnswer() . 
                              '</div>' .
                              '</div>';
        }

        $this->content .= '</div>';

        return $this;
    }

    public function withGameName($gameName) {
        $this->content .= '<div id="game-name">' . $gameName . '</div>';

        return $this;
    }

    public function withRoundNumber($roundNumber) {
        $this->content .= '<div id="round-number">Round ' . $roundNumber . '</div>';

        return $this;
    }

    public function withPlayers(Player ...$players) {
        $this->content .= '<div id="players">';

        foreach($players as $player) {
            $name = $player->getName();
            $token = $player->getToken();
            $me = $player->getIsMe() ? " me" : "";
            $judge = $player->getIsJudge() ? " judge" : "";
            $winner = $player->getIsWinner() ? " winner" : "";

            $this->content .= '<div class="player ' . $me . $judge . $winner . '"><div class="token bg-' . $token . '"></div><span class="name">' . $name . '</span></div>';
        }
        
        $this->content .= "</div>";

        return $this;
    }

    public function withCountdownTimer($startingFromSeconds) {
        $this->content .= '<div id="countdown-timer">' . $startingFromSeconds . '</div>';

        return $this;
    }

    public function withMessage($message) {
        $this->content .= '<div class="message">' . $message . '</div>';

        return $this;
    }

    public function withQuestions(Question ...$questions) {
        $this->content .= '<div id="questions">' .
                          '<form action="/' . $this->gameId . '/ask" method="post">';

        $tokens = Player::getTokens();

        shuffle($tokens);

        foreach($questions as $question) {
            $token = next($tokens);

            $this->content .= '<button class="question-button bg-color-' . $token . ' shadow" name="questionId" value="' . $question->getId() . '" type="submit">' . $question->getQuestion() . '</button>';
        }

        $this->content .= '</form>' .
                          '</div>';

        return $this;
    }

    public function withAnswer(?Answer $answer, $playerToken) {
        $disabled = ($answer) ? "disabled" : "";
        $answerText = ($answer) ? $answer->getAnswer() : "";
        $charCount = ($answer) ? max(0, 80 - strlen($answerText)) : 80;

        $this->content .= 
            '<div id="answer">' .
            '<form action="/' . $this->gameId . '/answer" method="post">' .
            '<textarea ' . $disabled . ' oninput="$(this).val($(this).val().replace(/\n/g, \'\')); $(\'#char-count\').text(Math.max(0, 80 - $(this).val().length));" maxlength="80" rows="1" class="bg-color-' . $playerToken . ' shadow white-text" placeholder="Write your answer" name="answer">' . $answerText . '</textarea>' . 
            '<span id="char-count">' . $charCount . '</span><button type="button" class="edit-button" onclick="const $textarea = $(this).siblings(\'textarea\'); const length = $textarea.val().length; $textarea.prop(\'disabled\', false); $textarea[0].setSelectionRange(length, length); $textarea.focus();"></button><button type="submit" class="save-button"></button>' .
            '</form>' .
            '</div>';

        return $this;
    }

    private function withJoinGameButton() {
        $this->content .= '<button class="button" id="join-game-button" onclick="showModal(\'#join-game-modal\');">Join Game</button>';

        return $this;
    }

    private function withCreateGameButton() {
        $this->content .= '<button class="button" id="create-game-button" onclick="showModal(\'#create-game-modal\');">Create Game</button>';

        return $this;
    }

    public function withStartGameButton() {
        $this->content .= '<button class="button" id="start-game-button">Start Game</button>';

        return $this;
    }

    public function withNextRoundButton() {
        $this->content .= '<button class="button" id="next-round-button">Next Round</button>';

        return $this;
    }

    public function withJoinGameButtonAndModal($availableTokens = []) {
        $modal = '<div id="join-game-modal" class="modal">' .
                 '<h2>Join Game</h2>' .
                 '<input placeholder="Your Name" name="playerName" maxlength="12" type="text">' .
                 '<h2>Your Token</h2>' .
                 '<div class="tokens">';

                
        foreach($availableTokens as $token) {
            $modal .= '<label>' .
                      '<input name="playerToken" type="radio" value="bg-' . $token . '">' . 
                      '<div class="token bg-' . $token . '"></div>' . 
                      '</label>';
        }

        $modal .= '</div>' .
                  '<button class="button">OK</button>' .
                  '</div>';

        $this->content .= $modal;

        return $this->withJoinGameButton();
    }

    public function withCreateGameButtonAndModal($availableTokens = []) {
        $modal = '<div id="create-game-modal" class="modal">' .
                 '<h2>Create Game</h2>' .
                 '<input placeholder="Game Name" name="gameName" maxlength="30" type="text">' .
                 '<input placeholder="Your Name" name="playerName" maxlength="12" type="text">' .
                 '<h2>Your Token</h2>' .
                 '<div class="tokens">';

                
        foreach($availableTokens as $token) {
            $modal .= '<label>' .
                      '<input name="playerToken" type="radio" value="' . $token . '">' . 
                      '<span class="token ' . $token . '"></span>' . 
                      '</label>';
        }

        $modal .= '</div>' .
                  '<button class="button">OK</button>' .
                  '</div>';

        $this->content .= $modal;

        return $this->withCreateGameButton();
    }

    public function render() {
        return '<div id="game">' . $this->content . "</div>";
    }
}
