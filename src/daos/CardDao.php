<?php
namespace Daos;

use Database\DbMapperInterface;
use Models\Card;

class CardDao implements CardDaoInterface {
    private DbMapperInterface $mapper;

    public function __construct(DbMapperInterface $mapper) {
        $this->mapper = $mapper;
    }

    public function getAll(): array {
        return $this->mapper->select("Models\\Card");
    }

    public function insert(Card $card) : Card {
        return $this->mapper->insert($card);
    }

    public function delete(Card $card) : Card {
        return $this->mapper->delete($card);
    }
}