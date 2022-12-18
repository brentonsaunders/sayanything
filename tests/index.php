<?php
require "../src/autoload.php";
require "autoload.php";

use Controllers\AppControllerTest;
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
use Views\GameViewTest;

const TEST_VIEWS = true;
const TEST_CONTROLLERS = false;
const TEST_SERVICES = false;
const TEST_DAOS = false;

if(TEST_VIEWS) {
    new GameViewTest();
}

if(TEST_CONTROLLERS) {
    new AppControllerTest();
}

if (TEST_SERVICES) {
    new GameServiceTest();
    new IdGeneratorTest();
}

if (TEST_DAOS) {
    new GameDaoTest();
    new PlayerDaoTest();
    new RoundDaoTest();
    new AnswerDaoTest();
    new VoteDaoTest();
    new QuestionDaoTest();
    new CardDaoTest();
}