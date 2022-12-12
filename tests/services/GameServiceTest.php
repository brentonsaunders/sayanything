<?php
namespace Services;

use Models\Game;
use Models\Round;
use Models\Player;
use Repositories\GameRepositoryInterface;
use Repositories\MockGameRepository;
use Services\IdGeneratorInterface;
use Services\MockIdGenerator;
use Test;

class GameServiceTest extends Test {
    private GameRepositoryInterface $gameRepository;
    private CardServiceInterface $cardService;
    private IdGeneratorInterface $idGenerator;

    public function __construct() {
        $this->gameRepository = new MockGameRepository();
        $this->cardService = new MockCardService();
        $this->idGenerator = new MockIdGenerator();
        
        parent::__construct();
    }

    public function createGame_createsNewGame() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        // WHEN
        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            $findGame->getName() === "Test Game" &&
            $findGame->getCreatorId() !== null &&
            is_array($findGame->getPlayers()) &&
            $findGame->getPlayer($findGame->getCreatorId())->getName() === "Player 1" &&
            $findGame->getPlayer($findGame->getCreatorid())->getToken() === Player::CLAPPERBOARD;
    }

    public function joinGame_addsPlayerToGame() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        // WHEN
        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $playerIdAndGame = $gameService->joinGame($game->getId(), "Player 2", Player::CAR);

        $playerId = $playerIdAndGame["playerId"];

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            count($findGame->getPlayers()) === 2 &&
            $findGame->getPlayer($playerId)->getName() === "Player 2" &&
            $findGame->getPlayer($playerId)->getToken() === Player::CAR &&
            $findGame->getPlayer($playerId)->getTurn() == 1;
    }

    public function startGame_startsANewRound() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        // WHEN
        $gameService->startGame($game->getId(), $game->getCreatorId());

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            $findGame->getState() === Game::ASKING_QUESTION &&
            $findGame->getJudge()->getId() === $game->getCreatorId() &&
            $findGame->getCurrentRound()->getId() !== null;
    }

    public function updateGame_judgeTookTooLongToAskQuestion_asksRandomQuestion() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $this->gameRepository->update($game);

        // WHEN
        $gameService->updateGame($game->getId());
        

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            $findGame->getState() === Game::ANSWERING_QUESTION &&
            $findGame->getCurrentRound()->getQuestionId() !== null;
    }

    public function newRound_afterFirstRound_makesTheCorrectPlayerJudge() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        $game = $this->gameRepository->getById($game->getId());

        $game->setState(Game::RESULTS);

        // WHEN
        $gameService->newRound($game->getId(), $game->getCreatorId());

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            $findGame->getState() === Game::ASKING_QUESTION &&
            $findGame->getJudge()->getName() === "Player 2";
    }

    public function askQuestion_asksQuestion() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        // WHEN
        $game = $this->gameRepository->getById($game->getId());

        $questions = $game->getCurrentRound()->getCard()->getQuestions();

        $question = $questions[rand(0, count($questions) - 1)];

        $gameService->askQuestion($game->getId(), $game->getJudge()->getId(), $question->getId());

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame !== null &&
            $findGame->getState() === Game::ANSWERING_QUESTION &&
            $findGame->getJudge()->getId() === $game->getCreatorId() &&
            $findGame->getCurrentRound()->getId() !== null &&
            $findGame->getCurrentRound()->getQuestionId() !== null;
    }

    public function answerQuestion_answersQuestion() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $playerIds = [];

        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $game = $gameService->updateGame($game->getId());

        // WHEN
        foreach($playerIds as $playerId) {
            $gameService->answerQuestion($game->getId(), $playerId, "Laborum deserunt velit magna consequat ipsum deserunt consectetur aliqua magna.");
        }

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        if($findGame === null) {
            return false;
        }

        $answers = $findGame->getCurrentRound()->getAnswers();

        if(count($answers) !== 5) {
            return false;
        }

        foreach($answers as $answer) {
            if(!$answer->getId()) {
                return false;
            }
        }

        return true;
    }

    public function vote_votes() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $playerIds = [];

        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $game = $gameService->updateGame($game->getId());

        foreach($playerIds as $playerId) {
            $gameService->answerQuestion($game->getId(), $playerId, "Laborum deserunt velit magna consequat ipsum deserunt consectetur aliqua magna.");
        }

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $game = $gameService->updateGame($game->getId());

        // WHEN
        foreach ($playerIds as $playerId) {
            $answers = $game->getCurrentRound()->getAnswers();

            shuffle($answers);

            $answer1Id = $answers[0]->getId();
            $answer2Id = $answers[1]->getId();

            $gameService->vote($game->getId(), $playerId, $answer1Id, $answer2Id);
        }

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        $votes = $findGame->getCurrentRound()->getVotes();

        return is_array($votes) && count($votes) === 5;
    }

    public function chooseAnswer_choosesAnswer() {
        // GIVEN
        $gameService = new GameService($this->gameRepository, $this->cardService,
            $this->idGenerator);

        $game = $gameService->createGame("Test Game", "Player 1", Player::CLAPPERBOARD);

        $playerIds = [];

        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 2", Player::CAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 3", Player::GUITAR);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 4", Player::MARTINI_GLASS);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 5", Player::FOOTBALL);
        ["playerId" => $playerIds[]] = $gameService->joinGame($game->getId(), "Player 6", Player::COMPUTER);

        $gameService->startGame($game->getId(), $game->getCreatorId());

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $game = $gameService->updateGame($game->getId());

        foreach($playerIds as $playerId) {
            $gameService->answerQuestion($game->getId(), $playerId, "Laborum deserunt velit magna consequat ipsum deserunt consectetur aliqua magna.");
        }

        $game->setTimeUpdated(date("Y-m-d H:i:s", strtotime("-120 seconds")));

        $game = $gameService->updateGame($game->getId());

        // WHEN
        $answers = $game->getCurrentRound()->getAnswers();

        shuffle($answers);

        $answer = $answers[0];

        $gameService->chooseAnswer($game->getId(), $game->getJudge()->getId(), $answer->getId());

        // THEN
        $findGame = $this->gameRepository->getById($game->getId());

        return $findGame->getCurrentRound()->getChosenAnswerId() !== null;
    }
}