<?php
namespace Controllers;
use Test;

class AppControllerTest extends Test {
    public function __construct() {
        parent::__construct();
    }

    public function get_returnsView() {
        $controller = new AppController();

        $controller->get();

        return false;
    }
}
