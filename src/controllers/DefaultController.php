<?php
namespace Controllers;

use App;
use Views\MainView;
use Views\TemplateView;

class DefaultController extends Controller {
    public function __construct(App $app, $requestMethod, $postData) {
        parent::__construct($app, $requestMethod, $postData);
    }

    public function index() {
        $params = $this->getParams();

        $gameId = $params["gameId"] ?? null;

        $view = new MainView($gameId);

        $view->render();
    }

    public function game($gameId) {
        print_r($gameId);
    }

    public function create() {
        echo "tesT";
        print_r($this->getPostData());
    }
}