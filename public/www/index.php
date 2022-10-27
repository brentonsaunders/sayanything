<?php
require '../../src/autoload.php';

// $app = new App();

use Models\Game;
use Models\Player;
use Daos\GameDao;
use Daos\PlayerDao;
use Repositories\GameRepository;

$db = new DatabaseHelper('localhost', 'sayanything', 'root', null);

$gameRepo = new GameRepository(new GameDao($db), new PlayerDao($db));

$game = $gameRepo->getById(4);

echo '<pre>'; print_r($game); echo '</pre>';
?>