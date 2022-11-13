<?php
namespace Views;

class MainView implements View {
    private $gameId;

    public function __construct($gameId = null) {
        $this->gameId = $gameId;
    }

    protected function head() {
        $gameId = ($this->gameId) ? "\"{$this->gameId}\"" : "null";

        echo "<title>Say Anything</title>";
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<link rel="stylesheet" href="css/index.css">';
        echo '<script src="js/jquery-3.6.1.min.js"></script>';
        echo "<script> const GAME_ID = $gameId; </script>";
        echo '<script src="js/index.js"></script>';
    }

    protected function body() {
        echo '<div id="app">';
        echo "<header>";

        $this->header();

        echo "</header>";

        echo "<main>";

        $this->main();

        echo "</main>";
        echo "</div>";
    }

    protected function header() {
        echo '<div class="left">';
        echo '<div id="menu">';
        echo "<div></div>";
        echo "<div></div>";
        echo "<div></div>";
        echo "</div>";
        echo "</div>";
        echo '<div class="center"><div id="title">Say Anything</div></div>';
        echo '<div class="right"></div>';
    }

    protected function main() {

    }

    public function render() {
        echo "<!DOCTYPE html>";
        echo "<html>";
        echo "<head>";

        $this->head();

        echo "</head>";
        echo "<body>";

        $this->body();

        echo "</body>";
        echo "</html>";
    }
}