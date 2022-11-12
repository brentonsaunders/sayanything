<?php
namespace Controllers;

use App;
use Views\MainView;
use Views\TemplateView;

class DefaultController extends Controller {
    public function __construct(App $app) {
        parent::__construct($app);
    }

    public function index() {
        $params = $this->getParams();

        $gameId = $params["gameId"] ?? null;

        $view = new MainView($gameId);

        $view->render();
    }
}