$(function() {
    if($('#lobby').length === 0) {
        return;
    }

    $.get("game", function(data) {
        if(data) {
            const $myGames = $("#my-games ul");

            $myGames.empty();

            data.forEach(function(game) {
                let round;

                if(game.roundNumber === null) {
                    round = "Waiting for Players";
                } else {
                    round = `Round: ${game.roundNumber}`;
                }

                let minutes = Math.floor(game.secondsSinceCreated / 60);
                let seconds = Math.floor(game.secondsSinceCreated % 60);
                let hours = Math.floor(minutes / 60);

                if(minutes < 10) {
                    minutes = "0" + minutes;
                }

                if(seconds < 10) {
                    seconds = "0" + seconds;
                }

                let timeSinceCreated;

                if(hours > 0) {
                    timeSinceCreated = hours + " hours";
                } else {
                    timeSinceCreated = minutes + ":" + seconds;
                }

                $myGames.append(`
                    <li>
                        <a class="game background ${game.myPlayer.playerToken}" href="?gameId=${game.gameId}">
                            <div class="left">
                                <span class="token ${game.myPlayer.playerToken}"></span>
                            </div>
                            <div class="center">
                                <span class="game-name">${game.gameName}</span>
                                <span class="players">Players: ${game.numPlayers}/8</span>
                                <span class="round">${round}</span>
                                <span class="time">${timeSinceCreated}</span>
                            </div>
                            <div class="right"></div>
                        </a>
                    </li>
                `);
            });
        }
    });
});