$(function() {
    $("#menu-button").on("click", () => {
        $("#app").addClass("sidebar-open");
    });

    $('#close-button').on("click", () => {
        $("#app").removeClass("sidebar-open");
    });

    $(document).on("submit", "form", function(e) {
        e.preventDefault();

        const method = $(this).attr("method");
        const url = $(this).attr("action");
        const formData = new FormData(e.target);

        $.ajax({
            method: method,
            url: url,
            data: [...formData]
        }).done(() => {

        });
    });
});

function select(token, answerId) {
    $("#select-o-matic").attr("class", token);
    $("#chosen-answer-token").class(`token bg-${token}`);
    $('input[name=chosenAnswerId]').val(answerId);
}

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