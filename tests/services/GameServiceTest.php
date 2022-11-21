<?php
namespace Services;

use Models\Game;
use Models\Player;
use Models\Round;
use Repositories\MockGameRepository;

class GameServiceTest {
    public function __construct() {
        assert_options(ASSERT_BAIL, 1);

        $this->updateGame_whenTimeUntilNewRoundHasPassed_beginsNewRound();
        $this->updateGame_judgeHasntChosenAnswer_picksTheMostPopularAnswer();
        $this->updateGame_playersTurnMustBeSkipped_skipsTurn();
        $this->joinGame_afterGameHasStarted_skipsTurn();

        echo "Tests have passed.<br>";
    }

    private function joinGame_afterGameHasStarted_skipsTurn() {
        // GIVEN
        $gameRepository = new class extends MockGameRepository {
            private Game $game;

            public function __construct() {
                parent::__construct();

                $this->game = new Game(
                    1,
                    "Test Game",
                    1,
                    Game::ASKING_QUESTION,
                    date( 'Y-m-d H:i:s', time()),
                    null
                );

                $this->game->setPlayers([
                    new Player(1, 1, "Test Creator", "guitar", 0, false, false)
                ]);
            }

            public function getById($id) {
                return $this->game;
            }

            public function insert(Game $game) : Game {
                $this->game = $game;

                return $this->game;
            }

            public function update(Game $game) {
                $this->game = $game;

                return $this->game;
            }
        };

        $gameService = new GameService(
            $gameRepository,
            new MockCardService()
        );

        // WHEN
        $gameService->joinGame(1, "Test Player", "football");

        // THEN
        $game = $gameService->getGame(1);

        assert(
            $game->getPlayerByToken("football")->getSkipTurn(),
            "Expected turn to be skipped when a player joins a game after it has started."
        );
    }

    private function updateGame_playersTurnMustBeSkipped_skipsTurn() {
        // GIVEN
        $gameRepository = new class extends MockGameRepository {
            private Game $game;

            public function __construct() {
                parent::__construct();

                $this->game = new Game(
                    1,
                    "Test Game",
                    1,
                    Game::RESULTS,
                    date( 'Y-m-d H:i:s', time() - 120),
                    null
                );

                $this->game->setPlayers([
                    new Player(1, 1, "Test Creator", "guitar", 0, false, false),
                    new Player(2, 1, "Test Player", "football", 1, true, false),
                    new Player(3, 1, "Test Player", "clapperboard", 2, false, false),
                ]);

                $this->game->setRounds([
                    new Round(1, 1, 1, null, null, null)
                ]);
            }

            public function getById($id) {
                return $this->game;
            }

            public function insert(Game $game) : Game {
                $this->game = $game;

                return $this->game;
            }

            public function update(Game $game) {
                $this->game = $game;

                $this->game->setTimeUpdated(date( 'Y-m-d H:i:s', time()));

                return $this->game;
            }
        };

        $gameService = new GameService(
            $gameRepository,
            new MockCardService()
        );

        // WHEN
        $gameService->updateGame(1);

        // THEN
        $game = $gameService->getGame(1);

        $rounds = $game->getRounds();

        $lastRound = $rounds[count($rounds) - 1];

        $player = $game->getPlayer(2);

        assert(
            $lastRound->getJudgeId() === 3 &&
            !$player->getSkipTurn(),
            "Expected turn to be skipped when a player joins a game after it has started."
        );
    }

    private function updateGame_whenTimeUntilNewRoundHasPassed_beginsNewRound() {
        // GIVEN
        $gameRepository = new class extends MockGameRepository {
            private Game $game;

            public function __construct() {
                parent::__construct();

                $this->game = new Game(
                    1,
                    "Test Game",
                    1,
                    Game::RESULTS,
                    date( 'Y-m-d H:i:s', time() - 120),
                    date( 'Y-m-d H:i:s', time() - 300)
                );
            }

            public function getById($id) {
                return $this->game;
            }

            public function update(Game $game) {
                $this->game = $game;
            }
        };

        $gameService = new GameService(
            $gameRepository,
            new MockCardService()
        );

        // WHEN
        $gameService->updateGame(1);

        // THEN
        $game = $gameRepository->getById(1);

        assert(
            $game->getState() === Game::ASKING_QUESTION,
            "Expected a new round to begin when time until next round has passed."
        );
    }

    private function updateGame_judgeHasntChosenAnswer_picksTheMostPopularAnswer() {

    }
}