<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Round;

class RoundDao implements RoundDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id): ?Round {
        $results = $this->mapper->select("Models\\Round", ["id" => $id]);

        return count($results) === 1 ? $results[0] : null;
    }

    public function getByGameId($gameId): array {
        return $this->mapper->select("Models\\Round", ["game_id" => $gameId]);
    }

    public function insert(Round $round) : Round {
        return $this->mapper->insert($round);
    }

    public function update(Round $round) : Round {
        return $this->mapper->update($round);
    }

    public function delete(Round $round) : Round {
        return $this->mapper->delete($round);
    }
}
