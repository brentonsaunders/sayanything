<?php
namespace Controllers;

use App;
use Views\HomeView;
use Views\GameView;
use Views\TemplateView;

class DefaultController extends Controller {
    public function __construct(App $app) {
        parent::__construct($app);
    }

    public function index() {
        /*
        $params = $this->getParams();

        $gameId = $params["gameId"] ?? null;

        if(!$gameId) {
            $view = new HomeView();

            $view->render();
        } else {
            $view = new GameView($gameId);

            $view->render();
        }
        */

        $view = new TemplateView(ROOT_DIR . "/templates/playground.html");

        $view->render();
    }
}