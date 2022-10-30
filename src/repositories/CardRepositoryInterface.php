<?php
namespace Repositories;

use Models\Card;

interface CardRepositoryInterface {
    public function getAll();
    public function getById($id);
}