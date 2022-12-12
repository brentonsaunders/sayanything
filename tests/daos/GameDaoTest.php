<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Game;
use Test;
class GameDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Game $testGame;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Game",
            "game",
            "id",
            [
                "id" => "id",
                "name" => "name",
                "creator_id" => "creatorId",
                "state" => "state",
                "current_round_id" => "currentRoundId",
                "time_updated" => "timeUpdated",
                "time_created" => "timeCreated"
            ]
        );

        $this->testGame = new Game();

        $this->testGame->setId("1");
        $this->testGame->setName("Test Game");
        $this->testGame->setState(Game::WAITING_FOR_PLAYERS);
        $this->testGame->setCurrentRoundId("1");
        $this->testGame->setTimeCreated(date("Y-m-d H:i:s"));

        parent::__construct();
    }

    public function getById_returnsGame() {
        // GIVEN
        $gameDao = new PlayerDao($this->mapper);

        // WHEN
        $game = $this->mapper->select("Models\\Game", ["id" => 1]);

        // THEN
        return $game !== null;
    }

    public function insert_returnsGame() {
        // GIVEN
        $gameDao = new PlayerDao($this->mapper);

        // WHEN
        $game = $this->mapper->insert($this->testGame);

        // THEN
        return $game !== null;
    }

    public function update_returnsGame() {
        // GIVEN
        $gameDao = new PlayerDao($this->mapper);

        // WHEN
        $game = $this->mapper->update($this->testGame);

        // THEN
        return $game !== null;
    }

    public function delete_returnsGame() {
        // GIVEN
        $gameDao = new PlayerDao($this->mapper);

        // WHEN
        $game = $this->mapper->delete($this->testGame);

        // THEN
        return $game !== null;
    }
}
