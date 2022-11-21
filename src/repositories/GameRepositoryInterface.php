<?php
namespace Repositories;

use Models\Game;

interface GameRepositoryInterface {
    public function getById($id);
    public function insert(Game $game) : Game;
    public function update(Game $game);
    public function delete(Game $game);
}
