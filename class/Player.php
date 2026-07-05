<?php

class Player
{
    public string $name;
    public bool $isAI;
    public array $handCards = [];

    public function __construct(string $name, bool $isAI)
    {
        $this->name = $name;
        $this->isAI = $isAI;
    }

    public function addCard(Card $card): void
    {
        $this->handCards[] = $card;
    }
    public function sortCards(): void
    {
        usort($this->handCards, function (Card $a, Card $b) {
        
            // 先比较点数
            if ($a->value != $b->value)
            {
                return $a->value <=> $b->value;
            }
        
            // 点数相同再比较花色
            return strcmp($a->suit, $b->suit);
        
        });
    }
}