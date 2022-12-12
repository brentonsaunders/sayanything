<?php
namespace Services;

interface IdGeneratorInterface {
    public function generateGameId();
    public function generatePlayerId();
    public function generateRoundId();
    public function generateAnswerId();
    public function generateVoteId();
}
