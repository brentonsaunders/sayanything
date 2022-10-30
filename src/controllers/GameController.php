<?php
namespace Controllers;

use App;
use Repositories\GameRepository;
use Services\GameService;

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
        $playerId = $params["playerId"] ?? null;

        $dto = $this->gameService->getDto($gameId, $playerId);

        $this->jsonResponse($dto->toArray());
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

        $dto = $this->gameService->createGame($gameName, $playerName, $playerToken);

        $this->jsonResponse($dto->toArray());
    }

    public function join() {
        
    }

    public function test() {
    }
}