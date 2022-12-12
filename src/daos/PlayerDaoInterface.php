<?php
namespace Daos;

use Models\Round;

interface PlayerDaoInterface {
    public function getById($id): ?Round;
    public function getByGameId($gameId): array;
    public function insert(Player $player): Round;
    public function update(Player $player): Round;
    public function delete(Player $player): Round;
}
