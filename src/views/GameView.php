<?php
namespace Views;

use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Round;

class GameView extends View {
    private $content = "";
    private $gameId = null;

    private function __construct($gameId) {
        $this->gameId = $gameId;
    }

    public static function builder($gameId) {
        return new GameView($gameId);
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

            $this->content .= '<div class="player ' . $me . $judge . $winner . '"><span class="token ' . $token . '"></span><span class="name">' . $name . '</span></div>';
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
        $this->content .= 
            '<div id="answer">' .
            '<form action="/' . $this->gameId . '/answer" method="post">' .
            '<textarea oninput="this.value = this.value.replace(/\n/g, \'\');" maxlength="80" rows="1" class="bg-color-' . $playerToken . ' shadow" placeholder="Write your answer" name="answer"></textarea>' . 
            '<button class="submit-button" type="submit">Submit</button>' .
            '<button class="edit-button" type="button">Edit</button>' .
            '</form>' .
            '</div>';

        return $this;
    }

    private function withJoinGameButton() {
        $this->content .= '<button class="button" id="join-game-button">Join Game</button>';

        return $this;
    }

    private function withCreateGameButton() {
        $this->content .= '<button class="button" id="create-game-button">Create Game</button>';

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
        $modal = '<div class="modal-container">' .
                 '<div class="modal">' .
                 '<h2>Join Game</h2>' .
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
                  '</div>' . 
                  '</div>';

        $this->content .= $modal;

        return $this->withJoinGameButton();
    }

    public function withCreateGameButtonAndModal($availableTokens = []) {
        $modal = '<div class="modal-container">' .
                 '<div class="modal">' .
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
                  '</div>' . 
                  '</div>';

        $this->content .= $modal;

        return $this->withCreateGameButton();
    }

    public function render() {
        return '<div id="game">' . $this->content . "</div>";
    }
}
