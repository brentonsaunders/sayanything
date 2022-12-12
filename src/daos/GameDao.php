<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Game;

class PlayerDao implements GameDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getById($id) : ?Game {
        $games = $this->mapper->select("Models\\Game", ["id" => $id]);

        if(count($games) === 1) {
            return $games[0];
        }

        return null;
    }

    public function insert(Game $game) : Game {
        return $this->mapper->insert($game);
    }

    public function update(Game $game) : Game {
        return $this->mapper->update($game);
    }

    public function delete(Game $game) : Game {
        return $this->mapper->delete($game);
    }
}
