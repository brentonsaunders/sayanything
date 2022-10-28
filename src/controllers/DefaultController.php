<?php
namespace Controllers;

use Views\View;

class DefaultController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        header("Content-Type: application/json");

        echo json_encode(["success" => true]);
    }
}