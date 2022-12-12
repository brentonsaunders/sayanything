<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Player;
use Test;
class PlayerDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Player $testPlayer;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Player",
            "players",
            "id",
            [
                "id" => "id",
                "game_id" => "gameId",
                "name" => "name",
                "token" => "token",
                "turn" => "turn",
                "skip_turn" => "skipTurn",
                "must_wait_for_next_round" => "mustWaitForNextRound"
            ]
        );

        $this->testPlayer = new Player();

        $this->testPlayer->setId("1");
        $this->testPlayer->setGameId("1");
        $this->testPlayer->setName("Test");
        $this->testPlayer->setToken(Player::CLAPPERBOARD);
        $this->testPlayer->setTurn("1");
        $this->testPlayer->setSkipTurn(false);
        $this->testPlayer->setMustWaitForNextRound(false);

        parent::__construct();
    }

    public function getById_returnsPlayer() {
        // GIVEN
        $playerDao = new PlayerDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Player", ["id" => 1]);

        // THEN
        return $player !== null;
    }

    public function getByGameId_returnsPlayers() {
        // GIVEN
        $playerDao = new PlayerDao($this->mapper);

        // WHEN
        $players = $this->mapper->select("Models\\Player", ["game_id" => 1]);

        // THEN
        return $players !== null;
    }

    public function insert_returnsPlayer() {
        // GIVEN
        $gameDao = new PlayerDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testPlayer);

        // THEN
        return $player !== null;
    }

    public function update_returnsPlayer() {
        // GIVEN
        $playerDao = new PlayerDao($this->mapper);

        // WHEN
        $player = $this->mapper->update($this->testPlayer);

        // THEN
        return $player !== null;
    }

    public function delete_returnsPlayer() {
        // GIVEN
        $playerDao = new PlayerDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testPlayer);

        // THEN
        return $player !== null;
    }
}
