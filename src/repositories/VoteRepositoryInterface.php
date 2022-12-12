<?php
namespace Repositories;

use Models\Vote;

interface VoteRepositoryInterface {
    public function getAll(): array;
    public function getById($id);
    public function getByRoundId($roundId);
    public function insertVote(Vote $vote) : Vote;
    public function insertVotes($votes) : array;
    public function updateVote(Vote $vote) : Vote;
    public function updateVotes($votes) : array;
    public function deleteVote(Vote $vote) : Vote;
    public function deleteVotes($votes) : array;
}
