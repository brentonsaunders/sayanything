<?php
require "../src/autoload.php";
require "autoload.php";

use Daos\GameDaoTest;
use Daos\PlayerDaoTest;
use Daos\RoundDaoTest;
use Services\GameServiceTest;

new GameServiceTest();
new GameDaoTest();
new PlayerDaoTest();
new RoundDaoTest();