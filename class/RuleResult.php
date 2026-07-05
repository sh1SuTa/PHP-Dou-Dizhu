<?php


class RuleResult
{
    // 牌型
    public int $type;

    // 主值
    public int $value;

    // 原始牌
    public array $cards;

    public function __construct(
        int $type,int $value,
        array $cards
    )
    {
        $this->type = $type;
        $this->value = $value;
        $this->cards = $cards;
    }
}
