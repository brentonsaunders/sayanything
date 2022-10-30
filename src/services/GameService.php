<?php
namespace Services;

use Exception;
use Models\Game;
use Models\Player;
use Models\Round;
use Repositories\GameRepository;
use Services\PlayerService;
use Services\RoundService;

class GameService {
    private PlayerService $playerService;
    private RoundService $roundService;
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository,
        PlayerService $playerService, RoundService $roundService) {
        $this->gameRepository = $gameRepository;
        $this->playerService = $playerService;
        $this->roundService = $roundService;
    }

    public function createGame($gameName, $playerName, $playerToken) {
        $id = $this->generateId();

        $game = new Game($id, $gameName, null, null, Game::WAITING_FOR_PLAYERS,
            null, null);

        $game = $this->gameRepository->insert($game);

        $player = $this->playerService->createPlayer($game->getId(), $playerName, $playerToken,
            false);

        $game->setCreatorId($player->getId());

        $this->gameRepository->update($game);

        return [
            "gameId" => $id,
            "playerId" => $player->getId()
        ];
    }

    public function joinGame($gameId, $playerName, $playerToken) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if(count($game->getPlayers()) >= 8) {
            throw new GameServiceException("Game is full!");
        }

        $skipTurn = false;

        if($game->getState() !== Game::WAITING_FOR_PLAYERS) {
            $skipTurn = true;
        }

        $player = $this->playerService->createPlayer($gameId, $playerName, $playerToken,
            $skipTurn);

        return $player->getId();
    }

    public function startGame($gameId, $playerId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getCreatorId() !== $playerId) {
            throw new GameServiceException("Only the player who created the game can start the game!");
        }

        if($game->getState() !== Game::WAITING_FOR_PLAYERS) {
            throw new GameServiceException("Game has already started!");
        }

        if(count($game->getPlayers()) < 4) {
            throw new GameServiceException("Not enough players to start game!");
        }

        $game->setState(Game::GAME_STARTED);

        $this->gameRepository->update($game);

        return $this->newRound($gameId);
    }

    public function newRound($gameId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getState() === Game::WAITING_FOR_PLAYERS) {
            throw new GameServiceException("Game hasn't started yet!");
        }

        $currentRound = $game->getCurrentRound();

        if(!$currentRound) {
            $player = $this->playerService->getFirstPlayer($gameId);
        } else {
            $player = $this->playerService->getNextPlayer($currentRound->getActivePlayerId());
        }

        $round = $this->roundService->createRound($gameId, $player->getId());

        $game->setCurrentRoundId($round->getId());

        $game->setState(Game::ASKING_QUESTION);

        $this->gameRepository->update($game);
    }

    public function askQuestion($gameId, $playerId, $questionId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getState() !== Game::ASKING_QUESTION) {
            throw new GameServiceException("Game is not looking for questions right now!");
        }

        $currentRoundId = $game->getCurrentRoundId();

        $this->roundService->askQuestion($currentRoundId, $playerId, $questionId);

        $game->setState(Game::ANSWERING_QUESTION);

        $this->gameRepository->update($game);
    }

    public function answerQuestion($gameId, $playerId, $answer) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getState() !== Game::ANSWERING_QUESTION) {
            throw new GameServiceException("Game is not looking for answers right now!");
        }

        if(!$game->hasPlayer($playerId)) {
            throw new GameServiceException("Not a valid player!");
        }

        $currentRoundId = $game->getCurrentRoundId();

        $this->roundService->answerQuestion($currentRoundId, $playerId, $answer);
    }

    public function startVoting($gameId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getState() !== Game::ANSWERING_QUESTION) {
            throw new GameServiceException("Voting can't happen at this time!");
        }

        $game->setState(Game::VOTING);

        $this->gameRepository->update($game);
    }

    public function vote($gameId, $playerId, $answerId) {
        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        if($game->getState() !== Game::VOTING) {
            throw new GameServiceException("Voting can't happen at this time!");
        }

        if(!$game->hasPlayer($playerId)) {
            throw new GameServiceException("Not a valid player!");
        }

        $currentRoundId = $game->getCurrentRoundId();

        $this->roundService->vote($currentRoundId, $playerId, $answerId);
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
            $digits .= strval(rand(0, 9));
        }

        return $digits;
    }
}

class GameServiceException extends Exception {}