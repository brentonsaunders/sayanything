$(function() {
    $('main').load("game?gameId=1126843686", () => {
        const $countdownTimer = $("#countdown-timer");

        if($countdownTimer.length > 0) {
            const countdown = () => setTimeout(() => {
                const startTime = parseInt($countdownTimer.text());

                $countdownTimer.text(startTime - 1);

                countdown();
            }, 1000);

            countdown();
        }
    });
});