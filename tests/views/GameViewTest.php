<?php
namespace Views;

use Models\Answer;
use Models\Card;
use Models\Player;
use Models\Question;
use Models\Vote;

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

        $answer = new Answer();

        $answer->setAnswer("My answer");

        $answers = [];
        $answerStrings = [
            "Laborum aliqua sint ad adipisicing proident est esse sint ea.",
            "Quis magna commodo minim nisi non ea proident ut laboris aliquip est adipisicing nulla.",
            "Amet deserunt cillum ex exercitation adipisicing.",
            "Aliquip eiusmod deserunt consectetur est commodo proident et adipisicing irure eu tempor.",
            "Esse velit fugiat commodo dolore officia occaecat aute Lorem duis mollit excepteur ex aute.",
            "Culpa nulla officia sint officia reprehenderit sit aliquip.",
            "Pariatur mollit commodo fugiat sunt proident minim ipsum anim tempor dolore ea anim."
        ];

        foreach($answerStrings as $index => $answerString) {
            $answer = new Answer();

            $answer->setId($index + 1);
            $answer->setAnswer($answerString);

            $answers[] = $answer;
        }
        
        $vote = new Vote();

        $vote->setAnswer1Id(1);
        $vote->setAnswer2Id(2);

        $vote2 = new Vote();

        $vote2->setAnswer1Id(1);
        $vote2->setAnswer2Id(4);


        $view = GameView::builder(1)
            ->withSidebar()
            ->withCountdownTimer(3)
            ->withMessage("Tap the answers to vote on which you believe is Brenton's favorite to the question")
            ->withMessage("Adipisicing ea do deserunt mollit pariatur reprehenderit nostrud elit Lorem cupidatat fugiat?")
            // ->withJoinGameButtonAndModal(Player::getTokens());
                // ->withMessage("Ullamco aliquip voluptate quis ex voluptate consequat Lorem irure proident.")
            ->withAnswers($answers, [$vote, $vote2], Player::CAR);
            //->withAnswer($answer, Player::DOLLAR_SIGN);
            // ->withQuestions(...$card->getQuestions());

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