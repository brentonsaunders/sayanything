<?php
namespace Daos;

use Models\Vote;

interface VoteDaoInterface {
    public function getById($id);
    public function getByRoundId($roundId);
    public function insert(Vote $vote) : Vote;
    public function update(Vote $vote) : Vote;
    public function delete(Vote $vote) : Vote;
}