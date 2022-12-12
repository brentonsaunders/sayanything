<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Vote;
use Test;
class VoteDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Vote $testVote;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Vote",
            "votes",
            "id",
            [
                "id" => "id",
                "round_id" => "roundId",
                "player_id" => "playerId",
                "answer1_id" => "answer1Id",
                "answer2_id" => "answer2Id",
            ]
        );

        $this->testVote = new Vote();

        $this->testVote->setId("1");
        $this->testVote->setRoundId("1");
        $this->testVote->setPlayerId("1");
        $this->testVote->setAnswer1Id("1");
        $this->testVote->setAnswer2Id("1");

        parent::__construct();
    }

    public function getById_returnsVote() {
        // GIVEN
        $playerDao = new VoteDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Vote", ["id" => 1]);

        // THEN
        return $player !== null;
    }

    public function getByRoundId_returnsVotes() {
        // GIVEN
        $playerDao = new VoteDao($this->mapper);

        // WHEN
        $players = $this->mapper->select("Models\\Vote", ["game_id" => 1]);

        // THEN
        return $players !== null;
    }

    public function insert_returnsVote() {
        // GIVEN
        $gameDao = new VoteDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testVote);

        // THEN
        return $player !== null;
    }

    public function update_returnsVote() {
        // GIVEN
        $playerDao = new VoteDao($this->mapper);

        // WHEN
        $player = $this->mapper->update($this->testVote);

        // THEN
        return $player !== null;
    }

    public function delete_returnsGame() {
        // GIVEN
        $playerDao = new VoteDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testVote);

        // THEN
        return $player !== null;
    }
}
