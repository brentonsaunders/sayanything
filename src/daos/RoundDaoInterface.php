<?php
namespace Daos;

use Models\Round;

interface RoundDaoInterface {
    public function getById($id);
    public function getByGameId($gameId);
    public function insert(Round $round) : Round;
    public function update(Round $round) : Round;
    public function delete(Round $round) : Round;
}