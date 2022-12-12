<?php
namespace Daos;

use Models\Round;

interface RoundDaoInterface {
    public function getAll(): array;
    public function getById($id): ?Round;
    public function getByGameId($gameId) : array;
    public function insert(Round $round) : Round;
    public function update(Round $round) : Round;
    public function delete(Round $round) : Round;
}