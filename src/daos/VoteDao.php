<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Vote;

class VoteDao implements VoteDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id): ?Vote {
        $results = $this->mapper->select("Models\\Vote", ["id" => $id]);

        return count($results) === 1 ? $results[0] : null;
    }

    public function getByRoundId($roundId): array {
        return $this->mapper->select("Models\\Vote", ["round_id" => $roundId]);
    }

    public function insert(Vote $vote) : Vote {
        return $this->mapper->insert($vote);
    }

    public function update(Vote $vote) : Vote {
        return $this->mapper->update($vote);
    }

    public function delete(Vote $vote) : Vote {
        return $this->mapper->delete($vote);
    }
}
