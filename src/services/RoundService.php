<?php
namespace Services;

use Exception;
use Models\Round;
use Daos\RoundDao;

class RoundService {
    private RoundDao $roundDao;

    public function __construct(RoundDao $roundDao) {
        $this->roundDao = $roundDao;
    }

    public function getRound($roundId) {
        return $this->roundDao->getById($roundId);
    }

    public function createRound($gameId, $activePlayerId) {
        $round = new Round(null, $gameId, $activePlayerId,  null, null);

        return $this->roundDao->insert($round);
    }
}

class RoundServiceException extends Exception {}