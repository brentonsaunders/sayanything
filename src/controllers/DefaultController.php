<?php
namespace Controllers;

use App;
use Services\GameService;
use Services\GameServiceException;
use Views\MainView;
use Views\GamePartialView;
use Views\LobbyView;

class DefaultController extends Controller {
    private GameService $gameService;

    public function __construct(App $app, $requestMethod, $postData) {
        parent::__construct($app, $requestMethod, $postData);

        $this->gameService = $app->getGameService();
    }

    public function lobby() {
        $games = [];

        try {
            foreach($_SESSION["games"] as $gameId => $playerId) {
                $game = $this->gameService->getGame($gameId);

                $player = $game->getPlayer($playerId);

                $round = $game->getRoundNumber();

                $games[] = [
                    "gameId" => $gameId,
                    "gameName" => $game->getName(),
                    "playerName" => $player->getName(),
                    "playerToken" => $player->getToken(),
                    "round" => $round
                ];
            }
        } catch(GameServiceException $e) {
            $this->badRequest();
        }

        $view = new LobbyView($games);

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
        $post = $this->getPostData();

        if(!(array_key_exists("gameName", $post) &&
             array_key_exists("playerName", $post) &&
             array_key_exists("playerToken", $post))) {
            $this->badRequest();
        }

        $gameName = $post["gameName"];
        $playerName = $post["playerName"];
        $playerToken = $post["playerToken"];

        $game = $this->gameService->createGame($gameName, $playerName, $playerToken);

        $_SESSION["games"][$game->getId()] = $game->getCreatorId();

        $this->jsonResponse([
            "redirect" => "./" . $game->getId()
        ]);
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

        $this->jsonResponse([
                "forceRefresh" => false
            ]);
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