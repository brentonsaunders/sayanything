<?php
use Daos\UserDao;

class App {
    public function __construct() {
        $config = new Configuration('localhost', 'brothasmentor', 'root', null);

        $db = new DatabaseHelper('localhost', 'brothasmentor', 'root', null);

        $router = new Router($_REQUEST);
    }
}