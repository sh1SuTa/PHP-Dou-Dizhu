<?php

class Card
{
    public int $value;
    public string $suit;
    public string $name;

    public function __construct(int $value, string $suit, string $name)
    {
        $this->value = $value;
        $this->suit = $suit;
        $this->name = $name;
        
    }
}