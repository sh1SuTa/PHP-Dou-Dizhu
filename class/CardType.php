<?php

class CardType
{
    public const INVALID = 0;

    public const SINGLE = 1;

    public const PAIR = 2;

    // 三张
    public const TRIPLE = 3;
    public const TRIPLE_WITH_ONE = 31;
    public const TRIPLE_WITH_PAIR = 32;

    // 顺子
    public const STRAIGHT = 4;
    // 连对
    public const PAIR_PAIR = 41;
    // 飞机
    public const PLANE = 42;
    //飞机带单
    public const PLANE_WITH_SINGLE = 43;
    // 飞机带对
    public const PLANE_WITH_PAIR = 44;

    

    // 炸弹
    public const BOMB = 5;
    // 四带二
    public const QUAD = 51;

    // 王炸
    public const JOKER_BOMB = 6;
}