<?php
namespace Services;

use Models\Card;
use Models\Question;

class MockCardService implements CardServiceInterface {
    public function __construct() { }

    public function getRandomCard($a) : Card {
        return new Card(1, [
            new Question(1, 1, "What celebrity would make the worst babysitter?"),
            new Question(2, 1, "What would make long car rides more fun?"),
            new Question(3, 1, "Where's the best place to have a birthday party?"),
        ]);
    }
}
