<?php
use Daos\AnswerDao;
use Daos\CardDao;
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\QuestionDao;
use Daos\VoteDao;
use Daos\RoundDao;
use Database\DbMapper;
use Database\PdoSession;
use Repositories\AnswerRepository;
use Repositories\CardRepository;
use Repositories\GameRepository;
use Repositories\PlayerRepository;
use Repositories\RoundRepository;
use Repositories\VoteRepository;
use Router\Request;
use Router\Result;
use Router\Router;
use Services\CardService;
use Services\GameService;
use Services\IdGeneratorService;

define('DEBUG', true);

class App {
    private $gameService = null;

    public function __construct() {
        date_default_timezone_set("US/Eastern");

        if(DEBUG) {
            $host = "localhost";
            $dbname = "sayanything";
            $username = "root";
            $password = "";
        } else {
            $host = "ijellyrollcom.ipagemysql.com";
            $dbname = "sayanything";
            $username = "sayanything";
            $password = "7UoF3k&95BZ*";
        }

        session_start();
        
        if(empty($_SESSION["games"])) {
            $_SESSION["games"] = [];
        }


        $router = $this->getRouter();

        $request = $_REQUEST;

        $url = $request["url"] ?? null;

        unset($request["url"]);

        $result = $router->route($url, $request, $_SERVER["REQUEST_METHOD"]);

        http_response_code($result->getResponseCode());

        echo $result->getResponse();
    }

    private function getRouter() {
        $router = new Router($this);

        $router->map(["GET"], "/", "Controllers\\AppController", "index");

        return $router;
    }


    public function getGameService() {
        if($this->gameService) {
            return $this->gameService;
        }

        $db = new PdoSession("localhost", "sayanything", "root", "");

        $mapper = new DbMapper($db);

        $mapper->map(
            "Models\\Answer",
            "answers",
            "id",
            [
                "id" => "id",
                "player_id" => "playerId",
                "round_id" => "roundId",
                "answer" => "answer",
            ]
        );

        $mapper->map(
            "Models\\Card",
            "cards",
            "id",
            [
                "id" => "id"
            ]
        );

        $mapper->map(
            "Models\\Game",
            "game",
            "id",
            [
                "id" => "id",
                "name" => "name",
                "creator_id" => "creatorId",
                "state" => "state",
                "current_round_id" => "currentRoundId",
                "time_updated" => "timeUpdated",
                "time_created" => "timeCreated"
            ]
        );

        $mapper->map(
            "Models\\Player",
            "players",
            "id",
            [
                "id" => "id",
                "game_id" => "gameId",
                "name" => "name",
                "token" => "token",
                "turn" => "turn",
                "skip_turn" => "skipTurn",
                "must_wait_for_next_round" => "mustWaitForNextRound"
            ]
        );

        $mapper->map(
            "Models\\Question",
            "questions",
            "id",
            [
                "id" => "id",
                "card_id" => "cardId",
                "question" => "question",
            ]
        );

        $mapper->map(
            "Models\\Round",
            "rounds",
            "id",
            [
                "id" => "id",
                "game_id" => "gameId",
                "round_number" => "roundNumber",
                "judge_id" => "judgeId",
                "card_id" => "cardId",
                "question_id" => "questionId",
                "chosen_answer_id" => "chosenAnswerId"
            ]
        );

        $mapper->map(
            "Models\\Vote",
            "votes",
            "id",
            [
                "id" => "id",
                "round_id" => "roundId",
                "player_id" => "playerId",
                "answer1_id" => "answer1Id",
                "answer2_id" => "answer2Id",
            ]
        );
        
        $answerDao = new AnswerDao($mapper);
        $cardDao = new CardDao($mapper);
        $gameDao = new GameDao($mapper);
        $playerDao = new PlayerDao($mapper);
        $questionDao = new QuestionDao($mapper);
        $roundDao = new RoundDao($mapper);
        $voteDao = new VoteDao($mapper);

        $answerRepository = new AnswerRepository($answerDao);
        $cardRepository = new CardRepository($cardDao, $questionDao);
        $playerRepository = new PlayerRepository($playerDao);
        $voteRepository = new VoteRepository($voteDao);
        $roundRepository = new RoundRepository($roundDao, $answerRepository, $cardRepository,
            $voteRepository);
        $gameRepository = new GameRepository($gameDao, $playerRepository, $roundRepository);

        $cardService = new CardService($cardRepository);

        $idGeneratorService = new IdGeneratorService(
            $gameRepository,
            $playerRepository,
            $roundRepository,
            $answerRepository,
            $voteRepository
        );

        $this->gameService = new GameService($gameRepository, $cardService, $idGeneratorService);

        return $this->gameService;
    }
}