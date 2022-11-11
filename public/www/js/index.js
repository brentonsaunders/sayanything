$(function() {
    const updateGame = () => {
        const $countdownTimer = $("#countdown-timer");

        if($countdownTimer.length > 0) {
            const countdown = () => setTimeout(() => {
                const startTime = parseInt($countdownTimer.text());

                $countdownTimer.text(startTime - 1);

                countdown();
            }, 1000);

            countdown();
        }
    }

    const loadGame = async() => {
        const $dontRefresh = $('*[data-dont-refresh="true"]');
        const $focus = $(":focus");
        const focusId = ($focus.length > 0) ? $focus.attr("id") : null;

        await new Promise(resolve => $('main').load("game?gameId=1126843686", () => resolve()));

        // Restore all the elements that aren't supposed to be refreshed
        $dontRefresh.each(function() {
            const id = $(this).attr("id");

            $(`#${id}`).replaceWith($(this));
        });

        $(`#${focusId}`).focus();

        updateGame();
    }

    loadGame();

    setInterval(() => {
        loadGame();
    }, 5000);

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