<?php
namespace Controllers;

use App;
use Models\Game;
use Models\ScoreBoard;
use Services\GameService;
use Services\GameServiceException;
use Services\ScoreService;
use Views\MainView;
use Views\LobbyView;
use Views\AnsweringQuestionView;
use Views\AskingQuestionView;
use Views\GameOverView;
use Views\JoinGameView;
use Views\ResultsView;
use Views\ScoreBoardView;
use Views\VotingView;
use Views\WaitingForNextRoundView;
use Views\WaitingForPlayersView;


use Views\TestGameView;
use Views\TestView;
use Models\Round;
use Models\Round;
use Models\Answer;
use Models\Question;
use Models\Card;
use Models\Vote;

class DefaultController extends Controller {
    private GameService $gameService;

    public function __construct(App $app, $requestMethod, $postData) {
        parent::__construct($app, $requestMethod, $postData);

        $this->gameService = $app->getGameService();
    }

    public function lobby() {
        $games = [];

        try {
            foreach($_SESSION["games"] as $gameId => $playerId) {
                $game = $this->gameService->getGame($gameId);

                $player = $game->getPlayer($playerId);

                $round = $game->getRoundNumber();

                $games[] = [
                    "gameId" => $gameId,
                    "gameName" => $game->getName(),
                    "playerName" => $player->getName(),
                    "playerToken" => $player->getToken(),
                    "round" => $round
                ];
            }
        } catch(GameServiceException $e) {
            $this->badRequest();
        }

        $view = new LobbyView($games);

        $view->render();
    }

    public function game($gameId) {
        $view = new MainView($gameId);

        $view->render();
    }

    public function test() {
        $game = $this->mockGame();

        $playerId = 1;

        $game->setState(Game::WAITING_FOR_PLAYERS);

        $state = $game->getState();

        if(!$playerId) {
            $testGameView = new JoinGameView($game);
        } else {
            $player = $game->getPlayer($playerId);

            if($player->getMustWaitForNextRound()) {
                $testGameView = new WaitingForNextRoundView($game, $playerId);
            } else {
                switch($state) {
                case Game::WAITING_FOR_PLAYERS:
                    $testGameView = new WaitingForPlayersView($game, $playerId);
                    break;
                case Game::ASKING_QUESTION:
                    $testGameView = new AskingQuestionView($game, $playerId);
                    break;
                case Game::ANSWERING_QUESTION:
                    $testGameView = new AnsweringQuestionView($game, $playerId);
                    break;
                case Game::VOTING:
                    $testGameView = new VotingView($game, $playerId);
                    break;
                case Game::RESULTS:
                case GAME::GAME_OVER:
                    // $testGameView = new ResultsView($game, $playerId);
                    $scoreService = new ScoreService($game);

                    $testGameView = new ScoreBoardView($game, $scoreService->getScoreBoard());
                    break;
                }
            }
        }

        $view = new TestView($testGameView);

        $view->render();
    }

    public function view($gameId) {
        try {
            $game = $this->gameService->getGame($gameId);
        } catch(GameServiceException $e) {
            $this->badRequest();
        }

        $playerId = null;

        if(array_key_exists($gameId, $_SESSION["games"])) {
            $playerId = $_SESSION["games"][$gameId];

            if(!$game->hasPlayer($playerId)) {
                $playerId = null;
            }
        }

        $state = $game->getState();

        if(!$playerId) {
            $view = new JoinGameView($game);
        } else {
            $player = $game->getPlayer($playerId);

            if($player->getMustWaitForNextRound()) {
                $view = new WaitingForNextRoundView($game, $playerId);
            } else {
                switch($state) {
                case Game::WAITING_FOR_PLAYERS:
                    $view = new WaitingForPlayersView($game, $playerId);
                    break;
                case Game::ASKING_QUESTION:
                    $view = new AskingQuestionView($game, $playerId);
                    break;
                case Game::ANSWERING_QUESTION:
                    $view = new AnsweringQuestionView($game, $playerId);
                    break;
                case Game::VOTING:
                    $view = new VotingView($game, $playerId);
                    break;
                case Game::RESULTS:
                    $view = new ResultsView($game, $playerId);
                    break;
                }
            }
        }

        $view->render();
    }

    public function create() {
        $post = $this->getPostData();

        if(!(array_key_exists("gameName", $post) &&
             array_key_exists("playerName", $post) &&
             array_key_exists("playerToken", $post))) {
            $this->badRequest();
        }

        $gameName = $post["gameName"];
        $playerName = $post["playerName"];
        $playerToken = $post["playerToken"];

        $game = $this->gameService->createGame($gameName, $playerName, $playerToken);

        $_SESSION["games"][$game->getId()] = $game->getCreatorId();

        $this->jsonResponse([
            "redirect" => "./" . $game->getId()
        ]);
    }

    public function join($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("playerName", $post) ||
           !array_key_exists("playerToken", $post)) {
            $this->badRequest();
        }

        if(array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerName = $post["playerName"];
        $playerToken = $post["playerToken"];

        $playerIdAndGame = $this->gameService->joinGame($gameId, $playerName, $playerToken);

        $playerId = $playerIdAndGame["playerId"];
        $game = $playerIdAndGame["game"];

        $_SESSION["games"][$gameId] = $playerId;
    }

    public function start($gameId) {
        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->startGame($gameId, $playerId);
    }

    public function nextRound($gameId) {
        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->newRound($gameId, $playerId);
    }

    public function ask($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("questionId", $post)) {
            $this->badRequest();
        }

        $questionId = $post["questionId"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->askQuestion($gameId, $playerId, $questionId);
    }

    public function answer($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("answer", $post)) {
            $this->badRequest();
        }

        $answer = $post["answer"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->answerQuestion($gameId, $playerId, $answer);
    }

    public function vote($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("vote1", $post) ||
           !array_key_exists("vote2", $post)) {
            $this->badRequest();
        }

        $vote1 = $post["vote1"];
        $vote2 = $post["vote2"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }
        
        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->vote($gameId, $playerId, $vote1,
            $vote2);

        $this->jsonResponse([
                "forceRefresh" => false
            ]);
    }

    public function chooseAnswer($gameId) {
        $post = $this->getPostData();

        if(!array_key_exists("answerId", $post)) {
            $this->badRequest();
        }

        $answerId = $post["answerId"];

        if(!array_key_exists($gameId, $_SESSION["games"])) {
            $this->badRequest();
        }

        $playerId = $_SESSION["games"][$gameId];

        $game = $this->gameService->chooseAnswer($gameId, $playerId, $answerId);
    }

    private function mockGame() {
        $game = new Game();

        $game->setId(1);
        $game->setName("Redbud Ballers");
        $game->setCreatorId(1);
        $game->setState(Game::RESULTS);
        $game->setTimeCreated(date('Y-m-d H:i:s', time() - 31));
        $game->setTimeUpdated(date('Y-m-d H:i:s', time()));

        $game->setPlayers([
            new Round(1, 1, "Brenton", Player::COMPUTER, 0, 0, 0),
            new Round(2, 1, "Prem", Player::MARTINI_GLASS, 1, 0, 0),
            new Round(3, 1, "TJ", Player::CAR, 2, 0, 0),

            new Round(4, 1, "Bablu", Player::DOLLAR_SIGN, 3, 0, 0),
            new Round(5, 1, "Saad", Player::CLAPPERBOARD, 4, 0, 0),

            /*
            new Player(6, 1, "Devesh", Player::HIGH_HEELS, 5, 0, 0),
            new Player(7, 1, "Brian", Player::FOOTBALL, 6, 0, 0),
            new Player(8, 1, "Tyler", Player::GUITAR, 7, 0, true),*/
        ]);

        $round = new Round(1, 1, 1, 1, 1, null);

        $round->setCard(new Card(1, [
            new Question(1, 1, "Which technology product would be the hardest to live without?"),
            new Question(2, 1, "What would be the most dangerous stunt for a movie stuntman?"),
            new Question(3, 1, "If I could train a monkey to do anything, what would it be?"),
            new Question(4, 1, "What would I most want to see constructed out of Legos?"),
            new Question(5, 1, "What's the best TV show to watch in re-runs?"),
        ]));

        $anotherRound = new Round(2, 1, 2, 2, 6, null);

        $anotherRound->setCard(new Card(2, [
            new Question(6, 2, "What's the most useless household item?"),
            new Question(7, 2, "What hit song should have never been recorded?"),
            new Question(8, 2, "If my life was a movie, what would it be?"),
            new Question(9, 2, "What living person would be the cooles to have dinner with?"),
            new Question(10, 2, "What's the worst place for a date?"),
        ]));

        $round->setAnswers([
            new Answer(1, 2, 1, "Instagram"),
            new Answer(2, 3, 1, "PS5"),
            new Answer(3, 4, 1, "Internal combustion engine"),
            new Answer(4, 5, 1, "X-ray imaging")
        ]);

        $anotherRound->setAnswers([
            new Answer(5, 1, 2, "Loofahs"),
            new Answer(6, 3, 2, "Tea candles"),
            new Answer(7, 4, 2, "Piggy banks"),
            new Answer(8, 5, 2, "Banana slicers"),
        ]);

        $round->setVotes([
            new Vote(1, 1, 2, 2), new Vote(1, 1, 2, 3),
            new Vote(1, 1, 3, 2), new Vote(1, 1, 3, 2),
            new Vote(1, 1, 4, 3), new Vote(1, 1, 4, 3),
            new Vote(1, 1, 5, 1), new Vote(1, 1, 5, 3),
        ]);

        $anotherRound->setVotes([
            new Vote(1, 1, 1, 6), new Vote(1, 1, 1, 6),
            new Vote(1, 1, 3, 5), new Vote(1, 1, 3, 7),
            new Vote(1, 1, 4, 7), new Vote(1, 1, 4, 3),
            new Vote(1, 1, 5, 7), new Vote(1, 1, 5, 5),
        ]);

        $rounds = [$round, $anotherRound];

        for($i = 0; $i < 9; ++$i) {
            $rounds[] = $round;
        }

        $game->setRounds($rounds);

        return $game;
    }
}