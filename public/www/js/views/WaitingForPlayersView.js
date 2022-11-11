class WaitingForPlayersView {
    constructor(game) {
        $("#game").html(`
            <div id="join-game">
                <h2>Join Game</h2>
                <form action="game/join" method="get">
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
                    <button>Join</button>
                </form>
            </div>
        `);
    }
}