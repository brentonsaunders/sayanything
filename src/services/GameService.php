<?php
namespace Services;

use Exception;
use Models\Game;
use Models\Player;
use Models\Round;
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\RoundDao;
use Services\PlayerService;

class GameService {
    const WAITING_FOR_PLAYERS = 'waiting-for-players';
    const CHOOSING_QUESTION = 'choosing-question';

    const MIN_PLAYERS = 4;

    private GameDao $gameDao;
    private PlayerDao $playerDao;

    public function __construct(
        GameDao $gameDao,
        PlayerDao $playerDao,
        RoundDao $roundDao
    ) {
        $this->gameDao = $gameDao;
        $this->playerDao = $playerDao;
        $this->roundDao = $roundDao;
    }

    /**
     * 
     */
    public function createGame($gameName, $creatorName, $creatorToken) {
        $gameFriendlyIdGenerator = new GameFriendlyIdGenerator($this->gameDao);

        $friendlyId = $gameFriendlyIdGenerator->generateFriendlyId();

        $game = new Game(null, $friendlyId, $gameName, null, null,
            self::WAITING_FOR_PLAYERS, null, null);

        $game = $this->gameDao->insert($game);

        $player = new Player(null, $game->getId(), $creatorName, $creatorToken,
            null);

        $player = $this->playerDao->insert($player);

        $game->setCreatorId($player->getId());

        $this->gameDao->update($game);

        return $player;
    }

    /**
     * 
     */
    public function joinGame($gameId, $playerName, $playerToken) {
        $game = $this->gameDao->getById($gameId);

        // Make sure this token isn't already in use
        if($this->playerDao->getByGameIdAndToken($gameId, $playerToken)) {
            throw new GameServiceException("Token is already being used!");
        }

        $player = new Player(null, $gameId, $playerName, $playerToken, null);

        $player = $this->playerDao->insert($player);

        return $player;
    }

    /**
     * 
     */
    public function startGame($gameId) {
        $game = $this->gameDao->getById($gameId);

        if($game->getState() !== self::WAITING_FOR_PLAYERS) {
            throw new GameServiceException("Game has already started!");
        }

        $players = $this->playerDao->getByGameId($gameId);

        if(count($players) < self::MIN_PLAYERS) {
            throw new GameServiceException("Not enough players to start the game!");
        }

        $playerService = new PlayerService($this->playerDao);

        $players = $playerService->orderPlayers($gameId);

        $game->setState(self::CHOOSING_QUESTION);

        $this->gameDao->update($game);

        $creatorId = $game->getCreatorId();

        $creator = $this->playerDao->getById($creatorId);

        return $creator;
    }
}

class GameServiceException extends Exception {}