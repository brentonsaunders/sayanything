<?php
namespace Services;
use Repositories\AnswerRepositoryInterface;
use Repositories\GameRepository;

use Repositories\GameRepositoryInterface;
use Repositories\PlayerRepositoryInterface;
use Repositories\RoundRepositoryInterface;
use Repositories\VoteRepositoryInterface;
class IdGeneratorService implements IdGeneratorServiceInterface {
    private GameRepositoryInterface $gameRepository;
    private PlayerRepositoryInterface $playerRepository;
    private RoundRepositoryInterface $roundRepository;
    private AnswerRepositoryInterface $answerRepository;
    private VoteRepositoryInterface $voteRepository;

    public function __construct(
        GameRepositoryInterface $gameRepository,
        PlayerRepositoryInterface $playerRepository,
        RoundRepositoryInterface $roundRepository,
        AnswerRepositoryInterface $answerRepository,
        VoteRepositoryInterface $voteRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->roundRepository = $roundRepository;
        $this->answerRepository = $answerRepository;
        $this->voteRepository = $voteRepository;
    }

	public function generateGameId() {
        return 1;
	}
	
	public function generatePlayerId() {
        return 1;
	}
	
	public function generateRoundId() {
        return 1;
	}
	
	public function generateAnswerId() {
        return 1;
	}
	
	public function generateVoteId() {
        return 1;
	}
}