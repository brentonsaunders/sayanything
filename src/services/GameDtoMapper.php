<?php
namespace Services;

use Exception;
use Dtos\WaitingForPlayersDto;
use Models\Game;

class GameDtoMapper {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
    }

    public function getDto() {
        if($this->game->getState() === Game::WAITING_FOR_PLAYERS) {
            return new WaitingForPlayersDto($this->game, $this->playerId);
        }

        throw new GameDtoMapperException("Invalid game state!");
    }
}

class GameDtoMapperException extends Exception {}