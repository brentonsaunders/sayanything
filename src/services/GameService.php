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
use Services\RoundService;

class GameService {
    const WAITING_FOR_PLAYERS = 'waiting-for-players';
    const CHOOSING_QUESTION = 'choosing-question';

    const MIN_PLAYERS = 4;
    const MAX_PLAYERS = 8;

    private GameDao $gameDao;
    private PlayerService $playerService;
    private RoundService $roundService;

    public function __construct(GameDao $gameDao, PlayerDao $playerDao, RoundDao $roundDao) {
        $this->gameDao = $gameDao;
        $this->playerService = new PlayerService($playerDao);
        $this->roundService = new RoundService($roundDao);
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

        $player = $this->playerService->createPlayer($game->getId(), $creatorName,
            $creatorToken);

        $game->setCreatorId($player->getId());

        $this->gameDao->update($game);

        return $player;
    }

    /**
     * 
     */
    public function joinGame($gameId, $playerName, $playerToken) {
        $game = $this->gameDao->getById($gameId);

        if($this->gameIsFull($gameId)) {
            return new GameServiceException("Game is full!");
        }

        $skipTurn = false;

        if($game->getState() !== self::WAITING_FOR_PLAYERS) {
            // The player has to wait for players who joined before him
            // to go before he can have his turn
            $skipTurn = true;
        }

        $player = $this->playerService->createPlayer($gameId, $playerName,
            $playerToken, $skipTurn);

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

        $players = $this->playerService->getPlayers($gameId);

        if(count($players) < self::MIN_PLAYERS) {
            throw new GameServiceException("Not enough players to start the game!");
        }

        $game->setState(self::CHOOSING_QUESTION);

        $this->gameDao->update($game);

        return $this->newRound($gameId);
    }

    /**
     * 
     */
    public function newRound($gameId) {
        $game = $this->gameDao->getById($gameId);

        $currentRoundId = $game->getCurrentRoundId();

        if($currentRoundId === null) {
            $activePlayerId = $game->getCreatorId();
        } else {
            $round = $this->roundService->getRound($currentRoundId);

            $lastActivePlayerId = $round->getActivePlayerId();

            $nextPlayer = $this->playerService->getNextPlayer($lastActivePlayerId);

            $activePlayerId = $nextPlayer->getId();
        }

        $round = $this->roundService->createRound($gameId, $activePlayerId);

        $game->setCurrentRoundId($round->getId());

        $this->gameDao->update($game);

        return $round;
    }

    /**
     * 
     */
    public function askQuestion($gameId, $playerId, $questionId) {

    }

    public function answerQuestion($gameId, $playerId, $answer) {

    }

    public function updateGame($gameId) {

    }
    
    private function gameIsFull($gameId) {
        $players = $this->playerService->getPlayers($gameId);

        if($players === null) {
            return null;
        }

        return count($players) >= self::MAX_PLAYERS;
    }
}

class GameServiceException extends Exception {}