<?php
namespace Repositories;

use Daos\CardDao;
use Daos\QuestionDao;
use Models\Card;
use Models\Question;

class CardRepository {
    private CardDao $cardDao;
    private QuestionDao $questionDao;

    public function __construct(CardDao $cardDao, QuestionDao $questionDao) {
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

    public function insert(Card $card) {
        $card = $this->cardDao->insert($card);

        $questions = $card->getQuestions();

        foreach($questions as &$question) {
            $question->setCardId($card->getId());

            $question = $this->questionDao->insert($question);
        }

        $card->setQuestions($questions);

        return $card;
    }

    public function delete(Card $card) {
        foreach($card->getQuestions() as $question) {
            $this->questionDao->delete($question);
        }

        $this->cardDao->delete($card);
    }
}