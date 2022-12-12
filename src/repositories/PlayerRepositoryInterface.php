<?php
namespace Repositories;

use Models\Round;

interface PlayerRepositoryInterface {
    public function getById($id);
    public function getByGameId($gameId);
    public function insertPlayer(Player $player) : Round;
    public function insertPlayers($players) : array;
    public function updatePlayer(Player $player) : Round;
    public function updatePlayers($players) : array;
    public function deletePlayer(Player $player) : Round;
    public function deletePlayers($players) : array;
}