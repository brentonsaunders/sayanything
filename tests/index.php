<?php
require "../src/autoload.php";
require "autoload.php";

use Daos\AnswerDaoTest;
use Daos\CardDaoTest;
use Daos\GameDaoTest;
use Daos\PlayerDaoTest;
use Daos\QuestionDaoTest;
use Daos\RoundDaoTest;
use Daos\VoteDaoTest;
use Services\GameServiceTest;
use Services\IdGenerator;
use Services\IdGeneratorTest;

new GameServiceTest();
new IdGeneratorTest();
new GameDaoTest();
new PlayerDaoTest();
new RoundDaoTest();
new AnswerDaoTest();
new VoteDaoTest();
new QuestionDaoTest();
new CardDaoTest();