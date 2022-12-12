<?php
namespace Repositories;

use Models\Card;
use Models\Question;

class MockCardRepository implements CardRepositoryInterface {
    private $cards = [];

    public function __construct() {
        $questionCounter = 1;

        for($i = 1; $i <= 50; ++$i) {
            $questions = [];

            for ($j = 0; $j < 5; ++$j) {
                $question = new Question();

                $question->setId($questionCounter++);
                $question->setCardId($i);
                $question->setQuestion("Ullamco irure eu exercitation esse culpa excepteur occaecat.");

                $questions[] = $question;
            }

            $card = new Card();

            $card->setId($i);
            $card->setQuestions($questions);

            $this->cards[$i] = $card;
        }
    }
	

	public function getAll() {
        return $this->cards;
	}
	
	
	public function getById($id) {
        return $this->cards[$id];
	}
}