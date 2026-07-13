<?php


class RuleResult
{
    // 牌型
    public int $type;

    // 主值
    public int $value;
    // 原始牌
    public array $cards;
    // 连续牌长度
    public int $length;

    public function __construct(
        int $type,int $value,
        array $cards,int $length= 1
    )
    {
        $this->type = $type;
        $this->value = $value;
        $this->cards = $cards;
        $this->length = $length;
    }
}
