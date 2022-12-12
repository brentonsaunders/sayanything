<?php
namespace Repositories;

use Daos\PlayerDaoInterface;
use Models\Player;

class PlayerRepository implements PlayerRepositoryInterface {
    private PlayerDaoInterface $playerDao;

    public function __construct(PlayerDaoInterface $playerDao) {
        $this->playerDao = $playerDao;
    }

    public function getAll(): array {
        return $this->playerDao->getAll();
    }

    public function getById($id) {
        return $this->playerDao->getById($id);
    }

    public function getByGameId($gameId) {
        return $this->playerDao->getByGameId($gameId);
    }

    public function insertPlayer(Player $player) : Player {
        return $this->playerDao->insert($player);
    }

    public function insertPlayers($players) : array {
        $arr = [];

        foreach($players as $player) {
            $arr[] = $this->playerDao->insert($player);
        }

        return $arr;
    }

    public function updatePlayer(Player $player) : Player {
        if(!$this->getById($player->getId())) {
            return $this->insertPlayer($player);
        }

        return $this->playerDao->update($player);
    }

    public function updatePlayers($players) : array {
        $arr = [];

        foreach($players as $player) {
            $arr[] = $this->updatePlayer($player);
        }

        return $arr;
    }

    public function deletePlayer(Player $player) : Round {
        return $this->playerDao->delete($player);
    }

    public function deletePlayers($players) : array {
        $arr = [];

        foreach($players as $player) {
            $arr[] = $this->playerDao->delete($player);
        }

        return $arr;
    }
}
