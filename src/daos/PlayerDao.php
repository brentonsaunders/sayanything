<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Player;

class PlayerDao implements PlayerDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getAll(): array {
        return $this->mapper->select("Models\\Player");
    }

    public function getById($id): ?Player {
        $results = $this->mapper->select("Models\\Player", ["id" => $id]);

        return (count($results) === 1) ? $results[0] : null;
    }

    public function getByGameId($gameId): array {
        return $this->mapper->select("Models\\Player", ["game_id" => $gameId]);
    }

    public function insert(Player $player): Player {
        return $this->mapper->insert($player);
    }

    public function update(Player $player): Player {
        return $this->mapper->update($player);
    }

    public function delete(Player $player): Player {
        return $this->mapper->delete($player);
    }
}