<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Round;
use Test;
class RoundDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Round $testRound;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Round",
            "rounds",
            "id",
            [
                "id" => "id",
                "game_id" => "gameId",
                "round_number" => "roundNumber",
                "judge_id" => "judgeId",
                "card_id" => "cardId",
                "question_id" => "questionId",
                "chosen_answer_id" => "chosenAnswerId"
            ]
        );

        $this->testRound = new Round();

        $this->testRound->setId("1");
        $this->testRound->setGameId("1");
        $this->testRound->setRoundNumber("1");
        $this->testRound->setJudgeId("1");
        $this->testRound->setCardId("1");
        $this->testRound->setQuestionId("1");
        $this->testRound->setChosenAnswerId("1");

        parent::__construct();
    }

    public function getById_returnsRound() {
        // GIVEN
        $playerDao = new RoundDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Round", ["id" => 1]);

        // THEN
        return $player !== null;
    }

    public function getByGameId_returnsRounds() {
        // GIVEN
        $playerDao = new RoundDao($this->mapper);

        // WHEN
        $players = $this->mapper->select("Models\\Round", ["game_id" => 1]);

        // THEN
        return $players !== null;
    }

    public function insert_returnsRound() {
        // GIVEN
        $gameDao = new RoundDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testRound);

        // THEN
        return $player !== null;
    }

    public function update_returnsRound() {
        // GIVEN
        $playerDao = new RoundDao($this->mapper);

        // WHEN
        $player = $this->mapper->update($this->testRound);

        // THEN
        return $player !== null;
    }

    public function delete_returnsRound() {
        // GIVEN
        $playerDao = new RoundDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testRound);

        // THEN
        return $player !== null;
    }
}
