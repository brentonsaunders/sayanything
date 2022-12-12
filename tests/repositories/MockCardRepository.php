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
                $questions[] = new Question(
                    $questionCounter++,
                    $i,
                    "Ullamco irure eu exercitation esse culpa excepteur occaecat."
                );
            }

            $this->cards[$i] = new Card($i, $questions);
        }
    }
	

	public function getAll() {
        return $this->cards;
	}
	
	
	public function getById($id) {
        return $this->cards[$id];
	}
}