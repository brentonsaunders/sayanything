<?php
namespace Services;

use Models\Card;
use Repositories\CardRepositoryInterface;

class CardService implements CardServiceInterface {
    private CardRepositoryInterface $cardRepository;

    public function __construct(CardRepositoryInterface $cardRepository) {
        $this->cardRepository = $cardRepository;
    }

    public function getRandomCard($otherThanCards) : Card {
        $cards = $this->cardRepository->getAll();

        $cardIds = array_map(function($card) {
            return $card->getId();
        }, $otherThanCards);

        $cards = array_filter($cards, function($card) use($cardIds) {
            return !in_array($card->getId(), $cardIds);
        });

        shuffle($cards);

        return $cards[0];
    }
}