<?php
namespace Services;

use Models\Game;
use Views\LobbyView;
use Views\View;

class GameViewMapper {
    private Game $game;
    private $playerId = null;

    public function __construct(Game $game, $playerId) {
        $this->game = $game;
        $this->playerId = $playerId;
    }

    public function getView() : View {
        return new LobbyView($this->game, $this->playerId !== null);
    }
}