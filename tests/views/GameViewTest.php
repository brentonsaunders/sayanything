<?php
namespace Views;

use Models\Card;
use Models\Player;
use Models\Question;

class GameViewTest {
    public function __construct() {

        $players = array_map(function ($token) {
            $player = new Player();

            $player->setId(1);
            $player->setGameId(1);
            $player->setName($this->getRandomName());
            $player->setToken($token);

            return $player;
        }, [Player::CLAPPERBOARD, Player::GUITAR, Player::CAR, Player::FOOTBALL,
        Player::HIGH_HEELS, Player::DOLLAR_SIGN, Player::COMPUTER, Player::MARTINI_GLASS]);

        $players[0]->setIsMe(true);
        $players[1]->setIsJudge(true);
        $players[2]->setIsWinner(true);

        $card = new Card();

        $questionStrings = [
            "Fugiat deserunt laborum ea consectetur laboris ut consectetur est cupidatat fugiat tempor sit id deserunt?",
            "Ea dolor cupidatat proident id do culpa?",
            "Voluptate eu cupidatat deserunt amet eu eu laboris minim amet laborum et fugiat?",
            "Occaecat labore adipisicing sit consectetur mollit eu ex proident?",
            "Sint minim incididunt consequat consectetur consectetur magna?"
        ];

        $questions = [];

        for($i = 0; $i < count($questionStrings); ++$i) {
            $questionString = $questionStrings[$i];

            // $questionString = "Occaecat ipsum amet do commodo. Laboris incididunt in in pariatur adipisicing qui est officia non commodo. Laborum aliqua minim id adipisicing commodo aliqua culpa ullamco voluptate duis tempor exercitation.";

            $question = new Question();

            $question->setId($i + 1);
            $question->setCardId(1);
            $question->setQuestion($questionString);

            $questions[] = $question;
        }

        $card->setId(1);
        $card->setQuestions($questions);

        $view = GameView::builder(1)
            ->withRoundNumber(2)
            ->withCountdownTimer(3)
            ->withMessage("In Brenton's Opinion ...")
            ->withMessage("Ullamco aliquip voluptate quis ex voluptate consequat Lorem irure proident.")
            // ->withAnswer(null, Player::DOLLAR_SIGN);
            ->withQuestions(...$card->getQuestions());

        echo (new MainView($view->render()))->render();
    }

    private function waitingForPlayersView(Player ...$players) {
        return GameView::builder()
            ->withGameName("Test Game")
            ->withPlayers(...$players)
            ->withMessage("Waiting for more players to join...");
    }

    private function getRandomName() {
        $letters = "abcdefghijklmnopqrstuvwxyz0123456789";
        $name = "";
        $length = rand(1, 12);

        for($i = 0; $i < $length; ++$i) {
            $name .= $letters[rand(0, strlen($letters) - 1)];
        }

        return $name;
    }
}