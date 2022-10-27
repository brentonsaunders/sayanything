<?php
namespace Services;

use Models\Game;
use Daos\GameDao;

class GameFriendlyIdGenerator {
    private GameDao $gameDao;

    public function __construct(GameDao $gameDao) {
        $this->gameDao = $gameDao;
    }

    public function generateFriendlyId() {
        do {
            $friendlyId = $this->randomTenDigits();
        } while(!$this->isUniqueFriendlyId($friendlyId));

        return $friendlyId;
    }

    private function isUniqueFriendlyId($friendlyId) {
        return $this->gameDao->getByFriendlyId($friendlyId) === null;
    }

    private function randomTenDigits() {
        $digits = '';

        for($i = 0; $i < 10; ++$i) {
            $digits .= rand(0, 9);
        }

        return $digits;
    }
}