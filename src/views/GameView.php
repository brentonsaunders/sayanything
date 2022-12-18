<?php
namespace Views;

use Models\Answer;
use Models\Card;
use Models\Game;
use Models\Player;
use Models\Question;
use Models\Round;
use Models\Vote;

class GameView extends View {
    private View $contentView;
    private $gameId;
    private $gameState;
    private $gameEndpoint;

    public function __construct($gameId, $gameState, $gameEndpoint, View $contentView) {
        $this->gameId = $gameId;
        $this->gameState = $gameState;
        $this->gameEndpoint = $gameEndpoint;
        $this->contentView = $contentView;
    }

    public function render() {
        return "<div id=\"game\" data-state=\"{$this->gameState}\">" .
               "<form id=\"form\" action=\"{$this->gameEndpoint}\" method=\"post\"></form>" . 
               $this->contentView->render() . 
               "</div>";
    }
}
