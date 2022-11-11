$(function() {
    const games = [
        {
            gameId: 4,
            gameName: "Redbud Ballers",
            playerToken: "guitar",
            numPlayers: 4,
            round: 3,
            secondsSinceCreated: 60 
        },
        {
            gameId: 4,
            gameName: "Redbud Ballers",
            playerToken: "car",
            numPlayers: 4,
            round: 3,
            secondsSinceCreated: 60 
        },
        {
            gameId: 4,
            gameName: "Redbud Ballers",
            playerToken: "computer",
            numPlayers: 4,
            round: 3,
            secondsSinceCreated: 60 
        }
    ];

    // const view = new LobbyView(games);

    const view = new JoinGameView();
});