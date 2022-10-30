<?php
require '../../src/autoload.php';

// $app = new App();

use Daos\AnswerDao;
use Daos\CardDao;
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\QuestionDao;
use Daos\VoteDao;
use Daos\RoundDao;
use Daos\TokensDao;
use Models\Answer;
use Models\Card;
use Models\Player;
use Models\Question;
use Models\Vote;
use Repositories\AnswerRepository;
use Repositories\CardRepository;
use Repositories\GameRepository;
use Repositories\PlayerRepository;
use Repositories\RoundRepository;
use Repositories\VoteRepository;
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
$voteDao = new VoteDao($db);

$answerRepository = new AnswerRepository($answerDao);

$cardRepository = new CardRepository($cardDao, $questionDao);

$playerRepository = new PlayerRepository($playerDao);

$voteRepository = new VoteRepository($voteDao);

$roundRepository = new RoundRepository($roundDao, $answerRepository, $cardRepository,
    $voteRepository);

$gameRepository = new GameRepository($gameDao, $playerRepository, $roundRepository);

$game = $gameRepository->getById("5481327893");

$round = $game->getRounds()[0];

$gameRepository->update($game);

echo "<pre>";

print_r($game);

echo "</pre>";
?>