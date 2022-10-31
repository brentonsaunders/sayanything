<?php
namespace Services;

use Models\Card;
use Repositories\CardRepositoryInterface;

class CardService {
    private CardRepositoryInterface $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository) {
        $this->cardRepository = $cardRepository;
    }

    public function getRandomCard() : Card {
        $cards = $this->cardRepository->getAll();

        shuffle($cards);

        return $cards[0];
    }
}