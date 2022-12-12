<?php
namespace Daos;

use Database\DbMapper;
use Database\DbMapperInterface;
use Database\MockDbSession;
use Models\Card;
use Test;
class CardDaoTest extends Test {
    private DbMapperInterface $mapper;
    private Card $testCard;

    public function __construct() {
        $this->mapper = new DbMapper(new MockDbSession());

        $this->mapper->map(
            "Models\\Card",
            "cards",
            "id",
            [
                "id" => "id"
            ]
        );

        $this->testCard = new Card();

        $this->testCard->setId("1");

        parent::__construct();
    }

    public function getAll_returnsCards() {
        // GIVEN
        $playerDao = new CardDao($this->mapper);

        // WHEN
        $player = $this->mapper->select("Models\\Card");

        // THEN
        return $player !== null;
    }

    public function insert_returnsCard() {
        // GIVEN
        $gameDao = new CardDao($this->mapper);

        // WHEN
        $player = $this->mapper->insert($this->testCard);

        // THEN
        return $player !== null;
    }

    public function delete_returnsCard() {
        // GIVEN
        $playerDao = new CardDao($this->mapper);

        // WHEN
        $player = $this->mapper->delete($this->testCard);

        // THEN
        return $player !== null;
    }
}
