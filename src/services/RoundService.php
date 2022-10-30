<?php
namespace Services;

use Exception;
use Daos\CardDao;
use Models\Card;
use Models\Question;
use Models\Round;
use Repositories\RoundRepository;

class RoundService {
    private RoundRepository $roundRepository;
    private $allCards = null;

    public function __construct(RoundRepository $roundRepository,
        CardDao $cardDao) {
        $this->roundRepository = $roundRepository;
        $this->allCards = $cardDao->getAll();
    }

    public function createRound($gameId, $activePlayerId) {
        $randomCard = $this->getRandomCard($gameId);

        $round = new Round(null, $gameId, $activePlayerId, $randomCard->getId(),
            null, null);

        return $this->roundRepository->insert($round);
    }

    public function askQuestion($roundId, $activePlayerId, $questionId) {
        $round = $this->roundRepository->getById($roundId);

        if(!$round) {
            throw new RoundServiceException("Round doesn't exist!");
        }

        if($round->getActivePlayerId() !== $activePlayerId) {
            throw new RoundServiceException("Only the judge can ask a question!");
        }

        if(!$round->getCard()->hasQuestion($questionId)) {
            throw new RoundServiceException("Not a valid question!");
        }

        $round->setQuestionId($questionId);

        $this->roundRepository->update($round);
    }

    public function answerQuestion($roundId, $playerId, $answer) {
        $round = $this->roundRepository->getById($roundId);

        if(!$round) {
            throw new RoundServiceException("Round doesn't exist!");
        }

        $round->addAnswer($playerId, $answer);

        $round = $this->roundRepository->update($round);
    }

    public function vote($roundId, $playerId, $answerId) {
        $round = $this->roundRepository->getById($roundId);

        if(!$round) {
            throw new RoundServiceException("Round doesn't exist!");
        }
    }

    private function getRandomCard($gameId) {
        $rounds = $this->roundRepository->getByGameId($gameId);

        if($rounds === null) {
            $availableCards = $this->allCards;
        } else {
            $usedCardIds = array_map(function($round) { return $round->getCardId(); }, $rounds);

            $availableCards = array_filter($this->allCards, function($card) use($usedCardIds) {
                return !in_array($card->getId(), $usedCardIds);
            });
        }

        shuffle($availableCards);

        $randomCard = $availableCards[0];

        return $randomCard;
    }
}

class RoundServiceException extends Exception {}