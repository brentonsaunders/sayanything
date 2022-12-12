<?php
namespace Services;

use Exception;
use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Round;
use Models\Vote;
use Repositories\GameRepositoryInterface;
use Services\CardService;
use Services\ScoreService;

class GameService {
    private GameRepositoryInterface $gameRepository;
    private CardServiceInterface $cardService;
    private IdGeneratorServiceInterface $idGeneratorService;

    public function __construct(
        GameRepositoryInterface $gameRepository,
        CardServiceInterface $cardService,
        IdGeneratorServiceInterface $idGeneratorService
    ) {
        $this->gameRepository = $gameRepository;
        $this->cardService = $cardService;
        $this->idGeneratorService = $idGeneratorService;
    }

    public function getGame($gameId) {
        try {
            $this->updateGame($gameId);
        } catch(GameServiceException $e) {
            throw $e;
        }

        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Game doesn't exist!");
        }

        return $game;
    }

    public function createGame($gameName, $playerName, $playerToken) {
        if(empty($gameName) ||
           empty($playerName) ||
           !Player::isToken($playerToken)) {
            throw new GameServiceException("Game name, player name, and player token must be given!");
        }

        $game = new Game();

        $gameId = $this->idGeneratorService->generateGameId();
        $playerId = $this->idGeneratorService->generatePlayerId();

        $game->setId($gameId);
        $game->setName($gameName);
        $game->setCreatorId($playerId);
        $game->setState(Game::WAITING_FOR_PLAYERS);
        $game->setTimeCreated(date("Y-m-d H:i:s"));

        $player = new Player();

        $player->setId($playerId);
        $player->setGameId($gameId);
        $player->setName($playerName);
        $player->setToken($playerToken);
        $player->setTurn(0);
        $player->setSkipTurn(false);
        $player->setMustWaitForNextRound(false);

        $game->addPlayer($player);

        $game = $this->gameRepository->insert($game);

        return $game;
    }

    public function joinGame($gameId, $playerName, $playerToken) {
        if(empty($playerName) ||
           !Player::isToken($playerToken)) {
            throw new GameServiceException("Player name and player token must be given!");
        }

        $game = $this->gameRepository->getById($gameId);

        if(!$game) {
            throw new GameServiceException("Invalid game!");
        }

        if(count($game->getPlayers()) >= Game::MAX_PLAYERS) {
            throw new GameServiceException("Game is full!");
        }

        if(in_array($playerToken, $game->getUsedTokens())) {
            throw new TokenAlreadyBeingUsedException("Token is already being used!");
        }
        
        $skipTurn = false;
        $mustWaitForNextRound = false;
        
        if($game->getState() !== Game::WAITING_FOR_PLAYERS) {
            $skipTurn = true;
            $mustWaitForNextRound = true;
        }

        $numPlayers = count($game->getPlayers());

        $player = new Player();

        $player->setId($this->idGeneratorService->generatePlayerId());
        $player->setGameId($game->getId());
        $player->setName($playerName);
        $player->setToken($playerToken);
        $player->setTurn($numPlayers);
        $player->setSkipTurn($skipTurn);
        $player->setMustWaitForNextRound($mustWaitForNextRound);

        $game->addPlayer($player);

        $game = $this->gameRepository->update($game);

        return [
            "playerId" => $player->getId(),
            "game" => $game
        ];
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

        if(count($game->getPlayers()) < Game::MIN_PLAYERS) {
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

        if($game->getRoundNumber() > 11) {
            throw new GameServiceException("The game ends after 11 rounds!");
        }

        if($playerId !== $game->getCreatorId()) {
            throw new GameServiceException("Only the creator of the game can start a new round!");
        }

        $game->allowPlayersWaitingForNextRound();

        $rounds = $game->getRounds();

        $playedCards = $game->getPlayedCards();

        $card = $this->cardService->getRandomCard($playedCards);

        $round = new Round();

        $round->setId($this->idGeneratorService->generateRoundId());
        $round->setGameId($game->getId());
        $round->setCardId($card->getId());

        if(!$rounds) {
            $round->setRoundNumber(1);
            $round->setJudgeId($game->getCreatorId());
        } else {
            $nextPlayer = $game->getNextPlayer();

            $round->setRoundNumber(count($rounds) + 1);
            $round->setJudgeId($nextPlayer->getId());
        }

        $game->addRound($round);
        $game->setCurrentRoundId($round->getId());

        $game->setState(Game::ASKING_QUESTION);
        $game->setTimeUpdated(date("Y-m-d H:i:s"));

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
        $game->setTimeUpdated(date("Y-m-d H:i:s"));

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function answerQuestion($gameId, $playerId, $answerText) {
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

        $answer = new Answer();

        $answer->setId($this->idGeneratorService->generateAnswerId());
        $answer->setPlayerId($playerId);
        $answer->setRoundId($currentRound->getId());
        $answer->setAnswer($answerText);

        $currentRound->addAnswer($answer);

        $game = $this->gameRepository->update($game);

        return $game;
    }

    public function vote($gameId, $playerId, $answer1Id, $answer2Id) {
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

        if(!$currentRound->hasAnswer($answer1Id) || !$currentRound->hasAnswer($answer2Id)) {
            throw new GameServiceException("Invalid answer!");
        }

        $vote = new Vote();

        $vote->setId($this->idGeneratorService->generateRoundId());
        $vote->setPlayerId($playerId);
        $vote->setRoundId($currentRound->getId());
        $vote->setAnswer1Id($answer1Id);
        $vote->setAnswer2Id($answer2Id);

        $currentRound->vote($vote);

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

        if(!$game->isJudge($playerId)) {
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
            if($game->secondsSinceLastUpdate() >= Game::SECONDS_TO_ASK_QUESTION) {
                $game->getCurrentRound()->askRandomQuestion();

                $game->setState(Game::ANSWERING_QUESTION);
                $game->setTimeUpdated(date("Y-m-d H:i:s"));
            }
        } else if($state === Game::ANSWERING_QUESTION) {
            if($game->secondsSinceLastUpdate() >= Game::SECONDS_TO_ANSWER_QUESTION) {
                if($game->lessThanTwoPlayersHaveAnswered()) {
                    $game->setState(Game::RESULTS);
                    $game->setTimeUpdated(date("Y-m-d H:i:s"));
                } else {
                    $game->setState(Game::VOTING);
                    $game->setTimeUpdated(date("Y-m-d H:i:s"));
                }
            }
        } else if($state === Game::VOTING) {
            if($game->secondsSinceLastUpdate() >= Game::SECONDS_TO_VOTE) {
                $game->setState(Game::RESULTS);
                $game->setTimeUpdated(date("Y-m-d H:i:s"));
            }
        } else if($state === Game::RESULTS) {
            if($game->isOver()) {
                return;
            }
            
            if($game->secondsSinceLastUpdate() >= Game::SECONDS_UNTIL_NEW_ROUND) {
                $this->newRound($gameId, $game->getCreatorId());

                return;
            }
        }

        $game = $this->gameRepository->update($game);

        return $game;
    }
}

class GameServiceException extends Exception {}
class TokenAlreadyBeingUsedException extends Exception {}