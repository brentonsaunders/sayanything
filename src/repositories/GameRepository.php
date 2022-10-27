<?php
namespace Repositories;

use DatabaseHelper;
use Models\Player;
use Models\Game;
use Daos\PlayerDao;
use Daos\GameDao;

class GameRepository {
    private GameDao $gameDao;
    private PlayerDao $playerDao;

    public function __construct(GameDao $gameDao, PlayerDao $playerDao) {
        $this->gameDao = $gameDao;
        $this->playerDao = $playerDao;
    }

    public function getById($id) {
        $game = $this->gameDao->getById($id);

        $players = $this->playerDao->getByGameId($game->getId());

        $game->setPlayers($players);

        return $game;
    }
}