class App {
    constructor() {
    }

    game(gameId) {
        const updateGame = function() {
            $.get(
                `/game?gameId=${gameId}`,
                function(data) {
                }
            ).fail(function() {
                window.location = "/";
            });
        };

        updateGame();

        setInterval(updateGame, 2000);
    }
}