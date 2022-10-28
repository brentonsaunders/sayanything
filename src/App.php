<?php
class App {
    public function __construct() {
        $db = new DatabaseHelper('localhost', 'sayanything', 'root', null);

        $router = new Router($_REQUEST);
    }
}