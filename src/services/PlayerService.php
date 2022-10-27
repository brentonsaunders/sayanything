<?php
namespace Services;

use Daos\PlayerDao;
use Daos\RoundDao;

class PlayerService {
    private PlayerDao $playerDao;
    private RoundDao $roundDao;

    public function __construct($playerDao, $roundDao) {
        $this->playerDao = $playerDao;
        $this->roundDao = $roundDao;
    }

    public function orderPlayers($gameId) {
        $players = $this->playerDao->getByGameId($gameId);

        shuffle($players);

        foreach($players as $order => &$player) {
            $player->setOrder($order);

            $this->playerDao->update($player);
        }

        return $players;
    }

    public function nextPlayer($gameId) {
        $game = $this->gameDao->getById($gameId);

        $currentRoundId = $game->getCurrentRoundId();

        $currentRound = $this->roundDao->getById($currentRoundId);

        $activePlayerId = $currentRound->getActivePlayerId();

        $activePlayer = $this->playerDao->getById($activePlayerId);
    }
}