<?php
namespace Controllers;

use Services/GameService;

class GamesController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if($this->getRequestMethod() === "POST") {
            return $this->postIndex();
        }

        $this->jsonResponse(["success" => true]);
    }

    private function postIndex() {
        $params = $this->getParams();

        if(!(array_key_exists("gameName", $params) &&
            array_key_exists( "playerName", $params) &&
            array_key_exists("playerToken", $params))) {
            $this->badRequest();
        }

        $this->jsonResponse(["success" => true]);
    }
}