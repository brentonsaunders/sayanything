<?php
namespace Services;

use Models\Card;
use Models\Question;
use Repositories\CardRepositoryInterface;
use Repositories\MockCardRepository;

class MockCardService implements CardServiceInterface {
    private CardRepositoryInterface $cardRepository;

    public function __construct() { 
        $this->cardRepository = new MockCardRepository();
    }

    public function getRandomCard($otherThanCards) : Card {
        $cards = $this->cardRepository->getAll();

        shuffle($cards);

        return $cards[0];
    }
}
