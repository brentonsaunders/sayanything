<?php
require '../../src/autoload.php';

use Models\Answer;
use Models\Card;
use Models\Player;
use Models\Question;
use Models\Vote;
use Services\GameService;

$app = new App();

$gameService = $app->getGameService();
?>