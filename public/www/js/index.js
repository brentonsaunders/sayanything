function vote(answerId) {
    let vote1 = $("#answers").data("vote1");
    let vote2 = $("#answers").data("vote2");
    const currentVote = $("#answers").data("current-vote") ?? 0;

    if(vote1 && vote2 && vote1 === vote2 && vote1 === answerId) {
        $("#answers").data("vote1", null);
        $("#answers").data("vote2", null);
        $("#answers").data("current-vote",  0);

        return;
    }

    if(currentVote === 0) {
        $("#answers").data("vote1", answerId);
    } else if(currentVote === 1) {
        $("#answers").data("vote2", answerId);
    }

    $("#answers").data("current-vote", (currentVote + 1) % 2);

    vote1 = $("#answers").data("vote1");
    vote2 = $("#answers").data("vote2");

    $("#vote1").show().appendTo(`.answer[data-answer-id=${vote1}] .tokens`);
    $("#vote2").show().appendTo(`.answer[data-answer-id=${vote2}] .tokens`);

    console.log($("#answers").data("vote1"), $("#answers").data("vote2"));
}

$(function() {
    $("#menu-button").on("click", () => {
        $("#app").addClass("sidebar-open");
    });

    $('#close-button').on("click", () => {
        $("#app").removeClass("sidebar-open");
    });

    $('.answer[data-answer-id]').on("click", function() {
        const answerId = $(this).data("answer-id");

        vote(answerId);
    });
});



function showModal(id) {
    const $modal = $(id);
    const $container = $(id).parent(".modal-container");

    $container.css("display", "grid");

    $modal.on("click", e => {
        e.stopPropagation();
    });

    $container.on("click", () => {
        $container.hide();
    });
}