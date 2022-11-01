<?php
namespace Services;

use Exception;
use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Vote;
use Repositories\GameRepository;
use Services\CardService;

class GameService {
    const MIN_PLAYERS = 4;
    const MAX_PLAYERS = 8;

    private GameRepository $gameRepository;
    private CardService $cardService;

    public function __construct(GameRepository $gameRepository,
        CardService $cardService) {
        $this->gameRepository = $gameRepository;
        $this->cardService = $cardService;
    }

    public function getGame($gameId) {
        try {
            $this->updateGame($gameId);
        } catch(GameServiceException $e) {
            throw $e;
        }

        return $this->gameRepository->getById($gameId);
    }

    public function createGame($gameName, $playerName, $playerToken) {
        $id = $this->generateId();

        $game = new Game($id, $gameName, null, Game::WAITING_FOR_PLAYERS,
            null, null);

        $player = new Player(null, $id, $playerName, $playerToken, null, null);

        $game->addPlayer($player);

        $game = $this->gameRepository->insert($game);

        $game->setCreatorId($game->getPlayers()[0]->getId());

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function joinGame($gameId, $playerName, $playerToken) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if(count($game->getPlayers()) >= self::MAX_PLAYERS) {
            throw new GameServiceException("Game is full!");
        }

        if(in_array($playerToken, $game->getUsedTokens())) {
            throw new TokenAlreadyBeingUsedException("Token is already being used!");
        }

        if($game->getState() === Game::WAITING_FOR_PLAYERS) {
        }

        $game->addPlayer($playerName, $playerToken);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function startGame($gameId, $playerId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if($game->getState() !== Game::WAITING_FOR_PLAYERS) {
            throw new GameServiceException("Game has already started!");
        }

        if($playerId !== $game->getCreatorId()) {
            throw new GameServiceException("Only the creator of the game can start the game!");
        }

        if(count($game->getPlayers()) < self::MIN_PLAYERS) {
            throw new GameServiceException("Not enough players to start the game!");
        }

        $game->setState(Game::GAME_STARTED);

        $game = $this->gameRepository->update($game);

        return $this->newRound($gameId, $playerId);
    }

    public function newRound($gameId, $playerId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        $state = $game->getState();

        if($state !== Game::GAME_STARTED && $state !== Game::RESULTS) {
            throw new GameServiceException("Can't start a new round!");
        }

        if($playerId !== $game->getCreatorId()) {
            throw new GameServiceException("Only the creator of the game can start a new round!");
        }

        $firstRound = $game->getRounds() === null;

        $card = $this->cardService->getRandomCard();

        if($firstRound) {
            $game->addRound($game->getCreatorId(), $card->getId());
        } else {
            $nextPlayer = $game->getNextPlayer();

            $game->addRound($nextPlayer->getId(), $card->getId());
        }

        $game->setState(Game::ASKING_QUESTION);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function askQuestion($gameId, $playerId, $questionId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if($game->getState() !== Game::ASKING_QUESTION) {
            throw new GameServiceException("Game isn't looking for questions at this time!");
        }

        if(!$game->hasPlayer($playerId)) {
            throw new GameServiceException("Player doesn't belong to this game!");
        }

        if(!$game->isJudge($playerId)) {
            throw new GameServiceException("Only the judge can ask a question!");
        }

        $currentRound = $game->getCurrentRound();

        if(!$currentRound->hasQuestion($questionId)) {
            throw new GameServiceException("Invalid question!");
        }

        $currentRound->setQuestionId($questionId);

        $game->setState(Game::ANSWERING_QUESTION);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function answerQuestion($gameId, $playerId, $answer) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if($game->getState() !== Game::ANSWERING_QUESTION) {
            throw new GameServiceException("Game isn't looking for answers at this time!");
        }

        if(!$game->hasPlayer($playerId)) {
            throw new GameServiceException("Player doesn't belong to this game!");
        }

        if($game->isJudge($playerId)) {
            throw new GameServiceException("The judge can't answer the question!");
        }

        $currentRound = $game->getCurrentRound();

        $currentRound->addAnswer($playerId, $answer);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function vote($gameId, $playerId, $answerId1, $answerId2) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if($game->getState() !== Game::VOTING) {
            throw new GameServiceException("Voting isn't happening right now!");
        }

        if(!$game->hasPlayer($playerId)) {
            throw new GameServiceException("Player doesn't belong to this game!");
        }

        if($game->getJudge()->getId() === $playerId) {
            throw new GameServiceException("The judge isn't allowed to vote!");
        }

        $currentRound = $game->getCurrentRound();

        if(!$currentRound->hasAnswer($answerId1) || !$currentRound->hasAnswer($answerId2)) {
            throw new GameServiceException("Invalid answer!");
        }

        $currentRound->vote($playerId, $answerId1, $answerId2);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function chooseAnswer($gameId, $playerId, $answerId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if($game->getState() !== Game::VOTING) {
            throw new GameServiceException("Voting isn't happening right now!");
        }

        if($game->getJudge()->getId() !== $playerId) {
            throw new GameServiceException("Only the judge can choose an answer!");
        }

        $currentRound = $game->getCurrentRound();

        if(!$currentRound->hasAnswer($answerId)) {
            throw new GameServiceException("Invalid answer!");
        }

        $currentRound->chooseAnswer($answerId);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function updateGame($gameId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        $seconds = $game->secondsSinceLastUpdate();

        $state = $game->getState();

        if($state === Game::ASKING_QUESTION) {
            if($game->secondsSinceLastUpdate() >= 120) {
                $game->getCurrentRound()->askRandomQuestion();

                $game->setState(Game::ANSWERING_QUESTION);
            }
        } else if($state === Game::ANSWERING_QUESTION) {
            if($game->everyPlayerHasAnswered() ||
               $game->secondsSinceLastUpdate() >= 120) {
                $game->setState(Game::VOTING);
            }
        } else if($state === Game::VOTING) {
            if(($game->everyPlayerHasVoted() &&
               $game->judgeHasChosenAnswer()) ||
               $game->secondsSinceLastUpdate() >= 120) {
                $game->setState(Game::RESULTS);
            }
        }

        $game = $this->gameRepository->update($game);
    }

    private function generateId() {
        do {
            $id = $this->randomTenDigits();
        } while(!$this->isIdUnique($id));

        return $id;
    }

    private function isIdUnique($id) {
        return $this->gameRepository->getById($id) === null;
    }

    private function randomTenDigits() {
        $digits = "";

        for($i = 0; $i < 10; ++$i) {
            $digits .= rand(0, 9);
        }

        return $digits;
    }
}

class GameServiceException extends Exception {}
class TokenAlreadyBeingUsedException extends Exception {}