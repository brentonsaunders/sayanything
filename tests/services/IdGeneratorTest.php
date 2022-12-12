<?php
namespace Services;

use Repositories\MockAnswerRepository;
use Repositories\MockGameRepository;
use Repositories\MockPlayerRepository;
use Repositories\MockRoundRepository;
use Repositories\MockVoteRepository;
use Test;

class IdGeneratorTest extends Test {
    private IdGenerator $idGenerator;

    public function __construct() {
        $this->idGenerator = new IdGenerator(
            new MockGameRepository(),
            new MockPlayerRepository(),
            new MockRoundRepository(),
            new MockAnswerRepository(),
            new MockVoteRepository()
        );

        parent::__construct();
    }

    public function generateGameId_generatesExpectedGameId() {
        // GIVEN & WHEN
        $result = $this->idGenerator->generateGameId();

        // THEN
        return preg_match("/^[0-9]{10}$/", $result) === 1;
    }

    public function generatePlayerId_generatesExpectedPlayerId() {
        // GIVEN & WHEN
        $result = $this->idGenerator->generatePlayerId();

        // THEN
        return $result == 2;
    }

    public function generateRoundId_generatesExpectedRoundId() {
        // GIVEN & WHEN
        $result = $this->idGenerator->generateRoundId();

        // THEN
        return $result == 2;
    }

    public function generateAnswerId_generatesExpectedAnswerId() {
        // GIVEN & WHEN
        $result = $this->idGenerator->generateAnswerId();

        // THEN
        return $result == 2;
    }

    public function generateVoteId_generatesExpectedVoteId() {
        // GIVEN & WHEN
        $result = $this->idGenerator->generateVoteId();

        // THEN
        return $result == 2;
    }
}