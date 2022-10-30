<?php
namespace Repositories;

use Daos\GameDaoInterface;
use Models\Game;
use Models\Player;
use Models\Round;
use Repositories\PlayerRepositoryInterface;
use Repositories\RoundRepositoryInterface;

class GameRepository {
    private GameDaoInterface $gameDao;
    private PlayerRepositoryInterface $playerRepository;
    private RoundRepositoryInterface $roundRepository;

    public function __construct(
        GameDaoInterface $gameDao, 
        PlayerRepositoryInterface $playerRepository,
        RoundRepositoryInterface $roundRepository
    ) {
        $this->gameDao = $gameDao;
        $this->playerRepository = $playerRepository;
        $this->roundRepository = $roundRepository;
    }

    public function getById($id) : Game {
        $game = $this->gameDao->getById($id);

        if(!$game) {
            return null;
        }

        $players = $this->playerRepository->getByGameId($id);

        $game->setPlayers($players);

        $rounds = $this->roundRepository->getByGameId($id);

        $game->setRounds($rounds);

        return $game;
    }

    public function insert(Game $game) : Game {
        $game = $this->gameDao->insert($game);

        $players = $game->getPlayers();

        if($players) {
            $game->setPlayers($this->playerRepository->insertPlayers($players));
        }

        $rounds = $game->getRounds();

        if($rounds) {
            $game->setRounds($this->roundRepository->insertRounds($rounds));
        }

        return $game;
    }

    public function update(Game $game) {
        $game = $this->gameDao->update($game);

        $players = $game->getPlayers();

        // Delete players that have been removed

        if($players) {
            $game->setPlayers($this->playerRepository->updatePlayers($players));
        }

        $rounds = $game->getRounds();

        if($rounds) {
            $game->setRounds($this->roundRepository->updateRounds($rounds));
        }

        return $game;
    }

    public function delete(Game $game) {
        $players = $game->getPlayers();

        if($players) {
            $this->playerRepository->deletePlayers($players);
        }

        $rounds = $game->getRounds();

        if($rounds) {
            $this->roundRepository->deleteRounds($rounds);
        }

        $this->gameDao->delete($game);

        return $game;
    }
}