<?php
use Daos\AnswerDao;
use Daos\CardDao;
use Daos\PlayerDao;
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
use Services\CardService;
use Services\GameService;
use Services\IdGeneratorService;

define('ROOT_DIR', realpath(__DIR__ . '/..'));

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

        $this->initGameService(new DatabaseHelper($host, $dbname, $username, $password));

        session_start();
        
        if(empty($_SESSION["games"])) {
            $_SESSION["games"] = [];
        }

        $router = new Router($this);

        $router->route($_REQUEST);
    }

    private function initGameService(DatabaseHelper $db) {
        $mapper = new DbMapper(new PdoSession("localhost", "sayanything", "root", ""));

        $mapper->map(
            "Models\\Game",
            "games",
            "id",
            [
                "id" => "id",
                "name" => "name",
                "creator_id" => "creatorId",
                "state" => "state",
                "time_updated" => "timeUpdated",
                "time_created" => "timeCreated",
            ]
        );
        
        $answerDao = new AnswerDao($db);
        $cardDao = new CardDao($db);
        $gameDao = new PlayerDao($mapper);
        $playerDao = new PlayerDao($db);
        $questionDao = new QuestionDao($db);
        $roundDao = new RoundDao($db);
        $voteDao = new VoteDao($db);

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
    }

    public function getGameService() {
        return $this->gameService;
    }
}