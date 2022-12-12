<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Round;

class PlayerDao implements PlayerDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id): ?Round {
        $results = $this->mapper->select("Models\\Player", ["id" => $id]);

        return (count($results) === 1) ? $results[0] : null;
    }

    public function getByGameId($gameId): array {
        return $this->mapper->select("Models\\Player", ["game_id" => $gameId]);
    }

    public function insert(Player $player): Round {
        return $this->mapper->insert($player);
    }

    public function update(Player $player): Round {
        return $this->mapper->update($player);
    }

    public function delete(Player $player): Round {
        return $this->mapper->delete($player);
    }
}