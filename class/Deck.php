<?php

require_once "Card.php";

class Deck
{
    public array $cards = [];

    public function __construct()
    {
        $this->createDeck();
    }

    private function createDeck()
    {
        $suits = ["♠", "♥", "♣", "♦"];

        $values = [
            3  => "3",
            4  => "4",
            5  => "5",
            6  => "6",
            7  => "7",
            8  => "8",
            9  => "9",
            10 => "10",
            11 => "J",
            12 => "Q",
            13 => "K",
            14 => "A",
            15 => "2"
        ];

        foreach ($values as $value => $text) {

            foreach ($suits as $suit) {

                $this->cards[] = new Card(
                    $value,
                    $suit,
                    $suit . $text
                );

            }

        }

        // 小王
        $this->cards[] = new Card(
            16,
            "",
            "🃏小王"
        );

        // 大王
        $this->cards[] = new Card(
            17,
            "",
            "🃏大王"
        );
    }

    public function shuffle()
    {
        shuffle($this->cards);
    }
    public function draw(): ?Card
    {
        return array_shift($this->cards);
    }
}