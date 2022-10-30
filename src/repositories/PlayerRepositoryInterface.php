<?php
namespace Repositories;

use Models\Player;

interface PlayerRepositoryInterface {
    public function getById($id);
    public function getByGameId($gameId);
    public function insertPlayer(Player $player) : Player;
    public function insertPlayers($players) : array;
    public function updatePlayer(Player $player) : Player;
    public function updatePlayers($players) : array;
    public function deletePlayer(Player $player) : Player;
    public function deletePlayers($players) : array;
}