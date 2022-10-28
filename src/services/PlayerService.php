<?php
namespace Services;

use Exception;
use Models\Player;
use Daos\PlayerDao;

class PlayerService {
    private PlayerDao $playerDao;

    public function __construct($playerDao) {
        $this->playerDao = $playerDao;
    }

    public function getPlayers($gameId) {
        return $this->playerDao->getByGameId($gameId);
    }

    /**
     * 
     */
    public function createPlayer($gameId, $playerName, $playerToken,
        $skipTurn = false) {
        // Make sure the token isn't already chosen
        $players = $this->getPlayers($gameId);

        if($players === null) {
            $player = new Player(null, $gameId, $playerName, $playerToken,
                0, false);

            return $this->playerDao->insert($player);
        }

        // Make sure the game isn't full
        if(count($players) >= 8) {
            throw new PlayerServiceException("The game is full!");
        }

        foreach($players as $player) {
            if($player->getToken() === $playerToken) {
                throw new PlayerServiceException("Token is already being used!");
            }
        }

        // Determine this player's turn
        $highestTurn = array_reduce($players, function($highestTurn, $player) {
            return max($highestTurn, $player->getTurn());
        }, 0);

        $player = new Player(null, $gameId, $playerName, $playerToken,
                $highestTurn + 1, $skipTurn);

        return $this->playerDao->insert($player);
    }

    public function getNextPlayer($playerId) {
        $thisPlayer = $this->playerDao->getById($playerId);

        if($thisPlayer === null) {
            return new PlayerServiceException("Invalid player!");
        }

        $gameId = $thisPlayer->getGameId();

        $players = $this->getPlayers($gameId);

        $numPlayers = count($players);

        $nextTurn = ($thisPlayer->getTurn() + 1) % $numPlayers;

        $player = $this->playerDao->getByGameIdAndTurn($gameId, $nextTurn);

        while($player->getSkipTurn()) {
            $player->setSkipTurn(false);

            $this->playerDao->update($player);

            $nextTurn = ($nextTurn + 1) % $numPlayers;

            $player = $this->playerDao->getByGameIdAndTurn($gameId, $nextTurn);
        }

        return $player;
    }
}

class PlayerServiceException extends Exception {}