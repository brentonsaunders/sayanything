<?php
namespace Views;

class GameView extends MainView {
    public function __construct($gameId) {
        parent::__construct();

        $this->setContents(<<<EOD

<div id="game">
    <h1>Say Anything</h1>
    <div id="game-view"></div>
    <script>
    $(function() {
        app.game("$gameId");
    });
    </script>
</div>

EOD
        );
    }
}
