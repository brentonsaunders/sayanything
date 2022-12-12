<?php
namespace Services;
use Repositories\AnswerRepositoryInterface;
use Repositories\GameRepository;

use Repositories\GameRepositoryInterface;
use Repositories\PlayerRepositoryInterface;
use Repositories\RoundRepositoryInterface;
use Repositories\VoteRepositoryInterface;
class IdGenerator implements IdGeneratorInterface {
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

        private function randomTenDigits() {
                $digits = "";

                for($i = 0; $i < 10; ++$i) {
                        $digits .= rand(0, 9);
                }

                return $digits;
        }

        public function generateGameId() {
                do {
                        $digits = $this->randomTenDigits();
                } while ($this->gameRepository->getById($digits));

                return $digits;
        }

        public function generatePlayerId() {
                $players = $this->playerRepository->getAll();

                return count($players) + 1;
        }

        public function generateRoundId() {
                $rounds = $this->playerRepository->getAll();

                return count($rounds) + 1;
        }

        public function generateAnswerId() {
                $answers = $this->answerRepository->getAll();

                return count($answers) + 1;
        }

        public function generateVoteId() {
                $votes = $this->voteRepository->getAll();

                return count($votes) + 1;
        }
}