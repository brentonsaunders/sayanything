<?php
namespace Models;

class Question {
    private $id = null;
    private $cardId = null;
    private $question = null;

    public function __construct($id, $cardId, $question) {
        $this->id = $id;
        $this->cardId = $cardId;
        $this->question = $question;
    }

    public function getId() {
        return $this->id;
    }

    public function getCardId() {
        return $this->cardId;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCardId($cardId) {
        $this->cardId = $cardId;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }
}