<?php
namespace Services;

use Models\Card;

interface CardServiceInterface {
    public function getRandomCard($otherThanCards) : Card;
}
