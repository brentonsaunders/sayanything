<?php
namespace Daos;

use DatabaseHelper;
use Models\Card;

class CardDao {
    private DatabaseHelper $db;

    public function __construct(DatabaseHelper $db) {
        $this->db = $db;
    }

    public function getAll() {
        $rows = $this->db->query("SELECT * FROM cards");

        $cards = [];

        foreach($rows as $row) {
            $cards[] = new Card($row["id"], null);
        }

        return $cards;
    }

    public function insert(Card $card) {
        $this->db->insert("cards", []);

        return new Card($this->db->lastInsertId(), $card->getQuestions());
    }

    public function delete(Card $card) {
        $this->db->query("DELETE FROM cards WHERE id = :id", [
            ":id" => $card->getId()
        ]);
    }
}