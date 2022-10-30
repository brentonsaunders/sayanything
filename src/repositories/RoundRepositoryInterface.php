<?php
namespace Repositories;

use Models\Round;

interface RoundRepositoryInterface {
    public function getById($id);
    public function getByGameId($gameId);
    public function insertRound(Round $round) : Round;
    public function insertRounds($rounds) : array;
    public function updateRound(Round $round) : Round;
    public function updateRounds($rounds) : array;
    public function deleteRound(Round $round) : Round;
    public function deleteRounds($rounds) : array;
}