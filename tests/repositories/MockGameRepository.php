<?php
namespace Repositories;

use Models\Card;
use Models\Game;
use Models\Question;

class MockGameRepository implements GameRepositoryInterface {
    private $games = [];
    private $rounds = [];
    private CardRepositoryInterface $cardRepository;

    public function __construct() {
        $this->cardRepository = new MockCardRepository();
    }

    public function getById($id) {
        $game = $this->games[$id] ?? null;

        if(!$game) {
            return null;
        }

        $rounds = $game->getRounds();

        if ($rounds) {
            foreach ($rounds as $round) {
                $round->setCard($this->cardRepository->getById($round->getCardId()));
            }
        }

        return $game;
    }

    public function insert(Game $game) : Game {
        $this->games[$game->getId()] = $game;

        return $this->getById($game->getId());
    }

    public function update(Game $game) {
        $this->games[$game->getId()] = $game;

        return $this->getById($game->getId());
    }

    public function delete(Game $game) {
        unset($this->games[$game->getId()]);
        
        return $game;
    }
}
