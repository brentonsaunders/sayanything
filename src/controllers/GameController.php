<?php
namespace Controllers;

use App;
use Dtos\GameDto;
use Services\GameService;
use Services\GameServiceException;
use Services\GameViewMapper;
use Views\GameView;
use Views\GamePartial;

class GameController extends Controller {
    private GameService $gameService;

    public function __construct(App $app) {
        parent::__construct($app);

        $this->gameService = $app->getGameService();
    }

    public function index() {
        $params = $this->getParams();

        if(!array_key_exists("gameId", $params)) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];

        try {
            $game = $this->gameService->getGame($gameId);
        } catch(GameServiceException $e) {
            $this->badRequest();
        }

        $view = new GamePartial($game, 11);

        $view->render();
    }

    private function all() {
        $myGames = $_SESSION["games"];

        if(count($myGames) === 0) {
            $this->badRequest();
        }

        $gameDtos = [];

        foreach($myGames as $myGame) {
            $game = $this->gameService->getGame($myGame["gameId"]);

            if($game) {
                $gameDtos[] = new GameDto($game, $myGame["playerId"]);
            }
        }

        $this->jsonResponse(GameDto::getArray($gameDtos));
    }

    public function create() {
        $params = $this->getParams();

        if(!(array_key_exists("gameName", $params) &&
             array_key_exists("playerName", $params) &&
             array_key_exists("playerToken", $params))) {
            $this->badRequest();
        }

        $gameName = $params["gameName"];
        $playerName = $params["playerName"];
        $playerToken = $params["playerToken"];

        $game = $this->gameService->createGame($gameName, $playerName, $playerToken);

        $_SESSION["games"][] = [
            "gameId" => $game->getId(),
            "playerId" => $game->getCreatorId()
        ];

        // $this->redirect("../?gameId={$game->getId()}");

        $this->redirect("..");
    }

    public function join() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) &&
             array_key_exists("playerName", $params) &&
             array_key_exists("playerToken", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerName = $params["playerName"];
        $playerToken = $params["playerToken"];

        $game = $this->gameService->joinGame($gameId, $playerName, $playerToken);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function start() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) &&
             array_key_exists("playerId", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];

        $game = $this->gameService->startGame($gameId, $playerId);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function newRound() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) &&
             array_key_exists("playerId", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];

        $game = $this->gameService->newRound($gameId, $playerId);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function askQuestion() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) && 
             array_key_exists("playerId", $params) &&
             array_key_exists("questionId", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];
        $questionId = $params["questionId"];

        $game = $this->gameService->askQuestion($gameId, $playerId, $questionId);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function answerQuestion() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) && 
             array_key_exists("playerId", $params) &&
             array_key_exists("answer", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];
        $answer = $params["answer"];

        $game = $this->gameService->answerQuestion($gameId, $playerId, $answer);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function vote() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) && 
             array_key_exists("playerId", $params) &&
             array_key_exists("answerId1", $params) &&
             array_key_exists("answerId2", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];
        $answerId1 = $params["answerId1"];
        $answerId2 = $params["answerId2"];

        $game = $this->gameService->vote($gameId, $playerId, $answerId1,
            $answerId2);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }

    public function chooseAnswer() {
        $params = $this->getParams();

        if(!(array_key_exists("gameId", $params) && 
             array_key_exists("playerId", $params) &&
             array_key_exists("answerId", $params))) {
            $this->badRequest();
        }

        $gameId = $params["gameId"];
        $playerId = $params["playerId"];
        $answerId = $params["answerId"];

        $game = $this->gameService->chooseAnswer($gameId, $playerId, $answerId);

        echo "<pre>";

        print_r($game);

        echo "</pre>";
    }
}