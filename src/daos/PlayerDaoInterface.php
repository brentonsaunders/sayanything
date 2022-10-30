<?php
namespace Daos;

use Models\Player;

interface PlayerDaoInterface {
    public function getById($id);
    public function getByGameId($gameId);
    public function insert(Player $player) : Player;
    public function update(Player $player) : Player;
    public function delete(Player $player) : Player;
}