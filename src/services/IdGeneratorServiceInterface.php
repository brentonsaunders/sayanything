<?php
namespace Services;

interface IdGeneratorServiceInterface {
    public function generateGameId();
    public function generatePlayerId();
    public function generateRoundId();
    public function generateAnswerId();
    public function generateVoteId();
}
