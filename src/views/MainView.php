<?php
namespace Views;

class MainView extends View {
    private View $gameView;
    private $gameId = null;
    private $gameState = null;
    private $gameEndpoint = null;

    public function __construct(View $gameView, $gameId, $gameState, $gameEndpoint) {
        $this->gameView = $gameView;
        $this->gameId = $gameId;
        $this->gameState = $gameState;
        $this->gameEndpoint = $gameEndpoint;
    }

    protected function head() {
        return "<title>Say Anything</title>" .
               '<meta charset="UTF-8">' .
               '<meta name="viewport" content="width=device-width, initial-scale=1.0">' .
               '<link rel="stylesheet" href="' . PUBLIC_HTML . '/css/index.css">' . 
               '<script src="' . PUBLIC_HTML . '/js/jquery-3.6.1.min.js"></script>' .
               '<script src="' . PUBLIC_HTML . '/js/index.js"></script>';
    }

    protected function body() {
        return '<div id="app">' . 
               "<header>" .
               $this->header() . 
               "</header>" .
               "<main>" .
               $this->main() .
               "</main>" .
               "</div>";
    }

    protected function header() {
        return  '<a id="menu-button">' .
                '<div class="hamburger"><span></span><span></span><span></span></div>' .
                "</a>" .
                '<div class="middle"><div id="title">Say Anything</div></div>' .
                '<div class="right"></div>';
    }

    protected function main() {
        return "<div id=\"game\" data-state=\"{$this->gameState}\">" .
               "<form id=\"form\" action=\"{$this->gameEndpoint}\" method=\"post\"></form>" . 
               $this->gameView->render() . 
               "</div>";
    }

    public function render() {
        return "<!DOCTYPE html>" .
               '<html lang="en">' . 
               "<head>" .
               $this->head() .
               "</head>" . 
               "<body>" .
               $this->body() .
               "</body>" .
               "</html>";
    }
}