<?php
namespace Services;

use Exception;
use Models\Player;
use Daos\PlayerDao;

class PlayerService {
    private PlayerDao $playerDao;

    public function __construct(PlayerDao $playerDao) {
        $this->playerDao = $playerDao;
    }

    public function createPlayer($gameId, $playerName, $playerToken, $skipTurn) {
        $players = $this->playerDao->getByGameId($gameId);

        $numPlayers = 0;

        if($players) {
            $numPlayers = count($players);

            if($numPlayers >= 8) {
                throw new PlayerServiceException("The game is full!");
            }

            $usedTokens = array_map(function($player) {
                return $player->getToken();
            }, $players);

            if(in_array($playerToken, $usedTokens)) {
                throw new PlayerServiceException("Token is already being used!");
            }
        }

        $player = new Player(null, $gameId, $playerName, $playerToken, $numPlayers + 1,
            $skipTurn);
        
        $player = $this->playerDao->insert($player);

        return $player;
    }

    public function getFirstPlayer($gameId) {
        $player = $this->playerDao->getByGameIdAndTurn($gameId, 1);

        if(!$player) {
            throw new PlayerServiceException("Game doesn't have any players!");
        }

        return $player;
    }

    public function getNextPlayer($playerId) {
        $player = $this->playerDao->getById($playerId);

        if(!$player) {
            throw new PlayerServiceException("Player doesn't exist!");
        }

        $gameId = $player->getGameId();
        $turn = $player->getTurn();
        
        $players = $this->playerDao->getByGameId($gameId);

        $numPlayers = count($players);

        $nextTurn = ($turn + 1) % $numPlayers;

        $nextPlayer = $this->playerWithTurn($players, $nextTurn);

        while($nextPlayer->getSkipTurn()) {
            $nextPlayer->setSkipTurn(false);

            $this->playerDao->update($nextPlayer);

            $nextTurn = ($nextTurn + 1) % $numPlayers;

            $nextPlayer = $this->playerWithTurn($players, $nextTurn);
        }

        return $nextPlayer;
    }

    private function playerWithTurn($players, $turn) {
        foreach($players as $player) {
            if($player->getTurn() == $turn) {
                return $player;
            }
        }

        return null;
    }

    private function skipPlayer($player) {

    }
}

class PlayerServiceException extends Exception {}