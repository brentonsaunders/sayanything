<?php
namespace Services;

class MockIdGenerator implements IdGeneratorInterface {
    private $playerCounter = 1;
    private $roundCounter = 1;
    private $answerCounter = 1;
    private $voteCounter = 1;

    public function __construct() {}

	public function generateGameId() {
        $id = "";

        for ($i = 0; $i < 10; ++$i) {
            $id .= rand(0, 9);
        }

        return $id;
	}
	
	public function generatePlayerId() {
        return $this->playerCounter++;
	}
	
	public function generateRoundId() {
        return $this->roundCounter++;
	}
	
	public function generateAnswerId() {
        return $this->answerCounter++;
	}
	
	public function generateVoteId() {
        return $this->voteCounter++;
	}
}