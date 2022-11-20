$(function() {
    
    const updateGame = () => {
        const $countdownTimer = $("#countdown-timer");

        if($countdownTimer.length > 0) {
            const countdown = () => setTimeout(() => {
                const startTime = parseInt($countdownTimer.text());

                if(startTime <= 0) {
                    $countdownTimer.text(0);
                } else {
                    $countdownTimer.text(startTime - 1);
                }

                countdown();
            }, 1000);

            countdown();
        }
    }

    const loadGame = async(forceRefresh = false) => {
        const $dontRefresh = $('*[data-dont-refresh="true"]:visible');
        const $focus = $(":focus");
        const focusId = ($focus.length > 0) ? $focus.attr("id") : null;

        await new Promise(resolve => $('main').load(`${GAME_ID}/view`, () => resolve()));

        if(!forceRefresh) {
            // Restore all the elements that aren't supposed to be refreshed
            $dontRefresh.each(function() {
                const id = $(this).attr("id");

                $(`#${id}`).replaceWith($(this));
            });

            $(`#${focusId}`).focus();
        }

        updateGame();
    }

    if(GAME_ID) {
        loadGame();

        setInterval(() => {
            // loadGame();
        }, 5000);
    }


    $(document).on("click", "#answer-picker label.answer-number input[type=radio]", function() {
        const value = $(this).val();

        $('#answers .answer').hide();

        $(`#answers .answer.${value}`).show();
    });

        

    $(document).on("submit", "form", function(e) {
        e.preventDefault();

        const method = $(this).attr("method");
        const actionUrl = $(this).attr("action");

        $.ajax({
            type: method,
            url: actionUrl,
            data: $(this).serialize(),
            success: function(data) {
                if(data.redirect) {
                    window.location = data.redirect;
                    return;
                }

                if(data.forceRefresh === false) {
                    return;
                }

                loadGame(true);
            }
        });
    });
    

    $(document).on("click", "div.modal", e => {
        e.stopPropagation();
    });

    $(document).on("click", "div.modal-overlay", function() {
        $(this).hide();
    });
});

function showModal(id) {
    const $modal = $(`#${id}.modal-overlay`);

    $modal.css("display", "flex");
}