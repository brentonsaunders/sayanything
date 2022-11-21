<?php
namespace Services;

use Models\Game;
use Repositories\MockGameRepository;

class GameServiceTest {
    public function __construct() {
        assert_options(ASSERT_BAIL, 1);

        $this->updateGame_whenTimeUntilNewRoundHasPassed_beginsNewRound();
        $this->updateGame_judgeHasntChosenAnswer_picksTheMostPopularAnswer();

        echo "Tests have passed.<br>";
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

    public updateGame_judgeHasntChosenAnswer_picksTheMostPopularAnswer() {

    }
}