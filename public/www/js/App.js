class App {
    constructor() {
    }

    game(gameId) {
        const updateGame = function() {
            $.get(
                `/game?gameId=${gameId}`,
                function(data) {
                    // Retain form data
                    const formData = [];

                    $("#game-view form input[type=text], " +
                      "#game-view form input[type=radio]:checked, " + 
                      "#game-view form input[type=checkbox]:checked, " + 
                      "#game-view form textarea").each(function() {
                        const name = $(this).attr("name");
                        const type = $(this).attr("type");
                        const value = $(this).val();
                        
                        formData.push({
                            name: name,
                            type: type,
                            value: value
                        });
                    });

                    $("#game-view").html(data);

                    formData.forEach(element => {
                        if(element.type === "radio" ||
                           element.type === "checkbox") {
                            $(`#game-view form input[type=${element.type}][name=${element.name}][value=${element.value}]`).prop("checked", true);
                        } else {
                            $(`#game-view form [name=${element.name}]`).val(element.value);
                        }
                    });
                }
            ).fail(function() {
                window.location = "/";
            });
        };

        updateGame();

        setInterval(updateGame, 2000);
    }
}