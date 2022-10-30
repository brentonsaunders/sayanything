<?php
require '../../src/autoload.php';

// $app = new App();

use Daos\AnswerDao;
use Daos\CardDao;
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\QuestionDao;
use Daos\RoundDao;
use Daos\TokensDao;
use Models\Card;
use Models\Player;
use Models\Question;
use Repositories\AnswerRepository;
use Repositories\CardRepository;
use Repositories\GameRepository;
use Repositories\PlayerRepository;
use Repositories\RoundRepository;
use Services\GameService;
use Services\RoundService;
use Services\PlayerService;

$db = new DatabaseHelper("localhost", "sayanything", "root", null);

$answerDao = new AnswerDao($db);
$cardDao = new CardDao($db);
$gameDao = new GameDao($db);
$playerDao = new PlayerDao($db);
$questionDao = new QuestionDao($db);
$roundDao = new RoundDao($db);
$tokensDao = new TokensDao($db);

$answerRepository = new AnswerRepository($answerDao);

$playerRepository = new PlayerRepository($playerDao);

$roundRepository = new RoundRepository($roundDao, $answerRepository);

$gameRepository = new GameRepository($gameDao, $playerRepository, $roundRepository);

$game = $gameRepository->getById("5481327893");

$game->getRounds()[0]->getAnswers()[0]->setAnswer("Pooping");

$gameRepository->update($game);

echo "<pre>";

print_r($game);

echo "</pre>";
?>