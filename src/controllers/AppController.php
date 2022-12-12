<?php
namespace Controllers;

use Router\Result;

class AppController {
    public function __construct() {

    }

    public function index() {
        return Result::ok();
    }
}
