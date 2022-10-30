<?php
namespace Controllers;

use Views\View;

class DefaultController extends Controller {
    public function __construct(App $app) {
        parent::__construct($app);
    }

    public function index() {
        header("Content-Type: application/json");

        echo json_encode(["success" => true]);
    }
}