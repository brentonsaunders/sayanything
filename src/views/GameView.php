<?php
namespace Views;

class GameView extends View {
    public function __construct($gameId) {
        parent::__construct();

        $this->setContents(<<<EOD
            <div id="game">
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
