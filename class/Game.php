<?php
class Action
{
    public int $player;

    public string $type; // play pass

    public array $cards = [];

    public function __construct(int $player, string $type, array $cards = [])
    {
        $this->player = $player;
        $this->type = $type;
        $this->cards = $cards;
    }
}
class Game
{
    public Player $player;

    public Player $ai1;

    public Player $ai2;

    public array $bottomCards = [];
    // 上一手牌
    public ?RuleResult $lastPlay = null;
    public int $lastPlayer = -1;
    // 当前轮到谁永远是012
    public int $currentPlayer = 0;
    // 连续Pass数量
    public int $passCount = 0;

    // 游戏是否结束
    public bool $gameOver = false;

    // 胜利者
    public int $winner = -1;
    // 游戏记录
    public array $gameRecord = [];
}