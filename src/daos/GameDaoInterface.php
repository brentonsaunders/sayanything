<?php
namespace Daos;

use Models\Game;

interface GameDaoInterface {
    public function getById($id);
    public function insert(Game $game) : Game;
    public function update(Game $game) : Game;
    public function delete(Game $game) : Game;
}