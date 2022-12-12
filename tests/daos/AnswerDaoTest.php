<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Answer;
use Test;
class AnswerDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Answer $testAnswer;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Answer",
            "answers",
            "id",
            [
                "id" => "id",
                "player_id" => "playerId",
                "round_id" => "roundId",
                "answer" => "answer",
            ]
        );

        $this->testAnswer = new Answer();

        $this->testAnswer->setId("1");
        $this->testAnswer->setPlayerId("1");
        $this->testAnswer->setRoundId("1");
        $this->testAnswer->setAnswer("1");

        parent::__construct();
    }

    public function getById_returnsAnswer() {
        // GIVEN
        $playerDao = new AnswerDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Answer", ["id" => 1]);

        // THEN
        return $player !== null;
    }

    public function getByRoundId_returnsAnswers() {
        // GIVEN
        $playerDao = new AnswerDao($this->mapper);

        // WHEN
        $players = $this->mapper->select("Models\\Answer", ["game_id" => 1]);

        // THEN
        return $players !== null;
    }

    public function insert_returnsAnswer() {
        // GIVEN
        $gameDao = new AnswerDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testAnswer);

        // THEN
        return $player !== null;
    }

    public function update_returnsAnswer() {
        // GIVEN
        $playerDao = new AnswerDao($this->mapper);

        // WHEN
        $player = $this->mapper->update($this->testAnswer);

        // THEN
        return $player !== null;
    }

    public function delete_returnsGame() {
        // GIVEN
        $playerDao = new AnswerDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testAnswer);

        // THEN
        return $player !== null;
    }
}
