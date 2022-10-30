<?php
namespace Repositories;

use Daos\CardDaoInterface;
use Daos\QuestionDaoInterface;
use Models\Card;
use Models\Question;

class CardRepository implements CardRepositoryInterface {
    private CardDaoInterface $cardDao;
    private QuestionDaoInterface $questionDao;

    public function __construct(
        CardDaoInterface $cardDao,
        QuestionDaoInterface $questionDao
    ) {
        $this->cardDao = $cardDao;
        $this->questionDao = $questionDao;
    }

    public function getAll() {
        $cards = $this->cardDao->getAll();

        foreach($cards as &$card) {
            $card = $this->getById($card->getId());
        }

        return $cards;
    }

    public function getById($id) {
        $questions = $this->questionDao->getByCardId($id);

        return new Card($id, $questions);
    }
}