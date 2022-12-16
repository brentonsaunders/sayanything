$(function() {
    $("#menu-button").on("click", () => {
        $("#app").addClass("sidebar-open");
    });

    $('#close-button').on("click", () => {
        $("#app").removeClass("sidebar-open");
    });

    $(document).on("click", "#select-o-matic div:not(.arrow)", function() {
        if($(this).hasClass("disabled")) {
            return;
        }

        console.log("test");

        const token = $(this).data("token");

        $("#select-o-matic").attr("class", token);
    });
});

function vote(answerId) {
    const vote1 = $("input[name=vote1]:checked").val();
    const vote2 = $("input[name=vote2]:checked").val();

    if(!vote1) {
        $(`input[name=vote1][value=${answerId}]`).prop("checked", true);
    } else if(!vote2) {
        $(`input[name=vote2][value=${answerId}]`).prop("checked", true);
    } else {
        if(answerId != vote1) {
            $(`input[name=vote1][value=${vote2}]`).prop("checked", true);
        }
        $(`input[name=vote2][value=${answerId}]`).prop("checked", true);
    }
}

function showModal(id) {
    const $modal = $(id);
    const $parent = $modal.parent();
    const $container = $(`<div class="modal-container"></div>`);
    
    $container.appendTo($parent);
    $modal.appendTo($container);

    $modal.on("click", e => {
        e.stopPropagation();
    });

    return new Promise(resolve => {
        $container.on("click", () => {
            $modal.appendTo($parent);

            $container.remove();

            resolve();
        });
    });
}