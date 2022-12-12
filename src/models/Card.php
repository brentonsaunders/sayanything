<?php
namespace Models;

use Models\Question;

class Card {
    private $id = null;
    private $questions = null;

    public function __construct() {}

    public function getId() {
        return $this->id;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }

    public function hasQuestion($questionId) {
        foreach($this->questions as $question) {
            if($question->getId() == $questionId) {
                return true;
            }
        }

        return false;
    }
}