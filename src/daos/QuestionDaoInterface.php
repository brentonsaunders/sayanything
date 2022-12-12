<?php
namespace Daos;

use Models\Question;

interface QuestionDaoInterface {
    public function getById($id): ?Question;
    public function getByCardId($cardId): array;
    public function insert(Question $question) : Question;
    public function update(Question $question) : Question;
    public function delete(Question $question) : Question;
}