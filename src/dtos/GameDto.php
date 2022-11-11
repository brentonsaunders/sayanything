<?php
namespace Dtos;

use Models\Game;

class GameDto {
    private Game $game;
    private $array = [];

    public function __construct(Game $game, $playerId) {
        $this->game = $game;

        $this->array["gameId"] = $game->getId();
        $this->array["gameName"] = $game->getName();
        $this->array["secondsSinceCreated"] = $game->secondsSinceCreated();
        $this->array["numPlayers"] = count($game->getPlayers());
        $this->array["roundNumber"] = $game->getRoundNumber();

        if($playerId) {
            $player = $game->getPlayer($playerId);

            if($player) {
                $this->array["myPlayer"] = [
                    "playerId" => $player->getId(),
                    "playerName" => $player->getName(),
                    "playerToken" => $player->getToken()
                ];
            }
        }
    }

    public function __call($method, $arguments) {
        if($method === "getArray") {
            return $this->array;
        }
    }

    public static function __callStatic($method, $arguments) {
        if($method === "getArray") {
            $array = [];

            $gameDtos = $arguments[0];

            foreach($gameDtos as $gameDto) {
                $array[] = $gameDto->getArray();
            }

            return $array;
        }
    }
}