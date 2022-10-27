<?php
require '../../src/autoload.php';

// $app = new App();

use Models\Game;
use Models\Player;
use Models\Round;
use Daos\GameDao;
use Daos\PlayerDao;
use Daos\RoundDao;
use Services\GameService;

$db = new DatabaseHelper('localhost', 'sayanything', 'root', null);

$gameService = new GameService(new GameDao($db), new PlayerDao($db),
    new RoundDao($db));

$player = $gameService->createGame('Redbud Ballers', 'Brenton', 'high-heels');

$gameId = $player->getGameId();

$gameService->joinGame($gameId, 'Bablu', 'car');
$gameService->joinGame($gameId, 'Saad', 'guitar');
$gameService->joinGame($gameId, 'Prem', 'clapperboard');
$gameService->joinGame($gameId, 'Devesh', 'computer');
$gameService->joinGame($gameId, 'TJ', 'martini-glass');

$gameService->startGame($gameId);
?>