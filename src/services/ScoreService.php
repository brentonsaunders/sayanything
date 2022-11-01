<?php
namespace Services;

use Models\Game;

class ScoreService {
    private Game $game;

    public function __construct(Game $game) {
        $this->game = $game;
    }
}