<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Question;
use Test;
class QuestionDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Question $testQuestion;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Question",
            "questions",
            "id",
            [
                "id" => "id",
                "card_id" => "cardId",
                "question" => "question",
            ]
        );

        $this->testQuestion = new Question();

        $this->testQuestion->setId("1");
        $this->testQuestion->setCardId("1");
        $this->testQuestion->setQuestion("Test Answer");

        parent::__construct();
    }

    public function getById_returnsQuestion() {
        // GIVEN
        $playerDao = new QuestionDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Question", ["id" => 1]);

        // THEN
        return $player !== null;
    }

    public function getByCardId_returnsQuestions() {
        // GIVEN
        $playerDao = new QuestionDao($this->mapper);

        // WHEN
        $players = $this->mapper->select("Models\\Question", ["game_id" => 1]);

        // THEN
        return $players !== null;
    }

    public function insert_returnsQuestion() {
        // GIVEN
        $gameDao = new QuestionDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testQuestion);

        // THEN
        return $player !== null;
    }

    public function update_returnsQuestion() {
        // GIVEN
        $playerDao = new QuestionDao($this->mapper);

        // WHEN
        $player = $this->mapper->update($this->testQuestion);

        // THEN
        return $player !== null;
    }

    public function delete_returnsQuestion() {
        // GIVEN
        $playerDao = new QuestionDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testQuestion);

        // THEN
        return $player !== null;
    }
}
