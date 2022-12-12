<?php
namespace Daos;

use Models\Vote;

interface VoteDaoInterface {
    public function getById($id): ?Vote;
    public function getByRoundId($roundId): array;
    public function insert(Vote $vote) : Vote;
    public function update(Vote $vote) : Vote;
    public function delete(Vote $vote) : Vote;
}