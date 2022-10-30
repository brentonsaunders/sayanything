<?php
namespace Daos;

use Models\Card;

interface CardDaoInterface {
    public function getAll();
    public function insert(Card $card) : Card;
    public function delete(Card $card) : Card;
}