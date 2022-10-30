<?php
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\TokensDao;
use Repositories\GameRepository;
use Services\GameService;
use Services\PlayerService;

class App {
    private GameService $gameService;

    public function __construct() {
        $db = new DatabaseHelper('localhost', 'sayanything', 'root', null);

        $playerDao = new PlayerDao($db);
        $gameDao = new GameDao($db);
        $tokensDao = new TokensDao($db);

        $playerService = new PlayerService(new PlayerDao($db));

        $gameRepository = new GameRepository($gameDao, $playerDao, $tokensDao);

        $this->gameService = new GameService($gameDao, $gameRepository, $playerService);

        $router = new Router($this);

        $router->route($_REQUEST);
    }

    public function getGameService() {
        return $this->gameService;
    }
}