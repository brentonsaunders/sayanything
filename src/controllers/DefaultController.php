<?php
namespace Controllers;

use App;
use Services\GameService;
use Services\GameServiceException;
use Views\MainView;
use Views\GamePartialView;

class DefaultController extends Controller {
    private GameService $gameService;

    public function __construct(App $app, $requestMethod, $postData) {
        parent::__construct($app, $requestMethod, $postData);

        $this->gameService = $app->getGameService();
    }

    public function lobby() {
        $view = new MainView();

        $view->render();
    }

    public function game($gameId) {
        $view = new MainView($gameId);

        $view->render();
    }

    public function view($gameId) {
        try {
            $game = $this->gameService->getGame($gameId);
        } catch(GameServiceException $e) {
            $this->badRequest();
        }

        $playerId = null;

        if(array_key_exists($gameId, $_SESSION["games"])) {
            $playerId = $_SESSION["games"][$gameId];

            if(!$game->hasPlayer($playerId)) {
                $playerId = null;
            }
        }

        $view = new GamePartialView($game, $playerId);

        $view->render();
    }

    public function create() {
        print_r($this->getPostData());
    }

    public function join($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("playerName", $post) ||
           !array_key_exists("playerToken", $post)) {
            $this->badRequest();
        }

        if(array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerName = $post["playerName"];
        $playerToken = $post["playerToken"];

        $playerIdAndGame = $this->gameService->joinGame($gameId, $playerName, $playerToken);

        $playerId = $playerIdAndGame["playerId"];
        $game = $playerIdAndGame["game"];

        $_SESSION["games"][$gameId] = $playerId;
    }

    public function start($gameId) {
        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->startGame($gameId, $playerId);
    }

    public function nextRound($gameId) {
        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->newRound($gameId, $playerId);
    }

    public function ask($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("questionId", $post)) {
            $this->badRequest();
        }

        $questionId = $post["questionId"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->askQuestion($gameId, $playerId, $questionId);
    }

    public function answer($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("answer", $post)) {
            $this->badRequest();
        }

        $answer = $post["answer"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->answerQuestion($gameId, $playerId, $answer);
    }

    public function vote($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("vote1", $post) ||
           !array_key_exists("vote2", $post)) {
            $this->badRequest();
        }

        $vote1 = $post["vote1"];
        $vote2 = $post["vote2"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->vote($gameId, $playerId, $vote1,
            $vote2);
    }

    public function chooseAnswer($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("answerId", $post)) {
            $this->badRequest();
        }

        $answerId = $post["answerId"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->chooseAnswer($gameId, $playerId, $answerId);
    }
}