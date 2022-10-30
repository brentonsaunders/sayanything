<?php
namespace Dtos;

use Models\Game;

class WaitingForPlayersDto implements GameDto {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    public function toArray() {
        if($this->playerId) {
            
        } else {
            return [
                "friendlyId" => $this->game->getFriendlyId(),
                "name" => $this->game->getName(),
                "state" => $this->game->getState(),
                "numPlayers" => count($this->game->getPlayers()),
                "remainingTokens" => $this->game->getRemainingTokens()
            ];
        }
    }
}
