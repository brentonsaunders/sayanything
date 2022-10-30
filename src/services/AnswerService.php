<?php
namespace Services;

use Repositories\AnswerRepository;

class AnswerService {
    private AnswerRepository $answerReposistory;

    public function __construct(AnswerRepository $answerReposistory) {
        $this->answerRepository = $answerReposistory;
    }
}