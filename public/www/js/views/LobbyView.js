class LobbyView {
    constructor(games) {
        $("#game").html(`
            <div id="my-games">
                <h2>My Games</h2>
                <ul id="games-list"></ul>
            </div>
            <div id="create-game">
                <h2>Create New Game</h2>
                <form action="game/create" method="get">
                    <input placeholder="Game Name" name="gameName" type="text">
                    <input placeholder="Your Name" name="playerName" type="text">
                    <h3>Your Token</h2>
                    <div id="tokens">
                        <label><input name="playerToken" type="radio" value="guitar"><span class="token guitar"></span></label>
                        <label><input name="playerToken" type="radio" value="computer"><span class="token computer"></span></label>
                        <label><input name="playerToken" type="radio" value="clapperboard"><span class="token clapperboard"></span></label>
                        <label><input name="playerToken" type="radio" value="car"><span class="token car"></span></label>
                        <label><input name="playerToken" type="radio" value="martini-glass"><span class="token martini-glass"></span></label>
                        <label><input name="playerToken" type="radio" value="dollar-sign"><span class="token dollar-sign"></span></label>
                        <label><input name="playerToken" type="radio" value="football"><span class="token football"></span></label>
                        <label><input name="playerToken" type="radio" value="high-heels"><span class="token high-heels"></span></label>
                    </div>
                    <button>Create</button>
                </form>
            </div>
        `);

        games.forEach(game => {
            $("#game #games-list").append(`
                <li>
                    <a href="?gameId=${game.gameId}" class="background ${game.playerToken}">
                        <div class="left">
                            <span class="token ${game.playerToken}"></span>
                        </div>
                        <div class="center">
                            <span class="game-name">${game.gameName}</span>
                            <div>
                                <span class="num-players">${game.numPlayers}</span>
                                <span class="round">${game.round}</span>
                                <span class="time">${game.secondsSinceCreated}</span>
                            </div>
                        </div>
                        <div class="right"><span></span></div>
                    </a>
                </li>
            `);
        });
    }
}