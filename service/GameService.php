<?php
class GameService
{
    public function play(Game $game, array $indexes): array
    {
        $currentPlayer = $game->currentPlayer;
        $player = $this->getPlayer($game, $currentPlayer);
        return $this->playForPlayer($game, $player, $indexes);
    }

    private function getPlayer(Game $game, int $playerIndex): Player
    {
        switch ($playerIndex) {
            case 0:
                return $game->player;
            case 1:
                return $game->ai1;
            case 2:
                return $game->ai2;
            default:
                throw new Exception("无效的玩家索引");
        }
    }

    private function playForPlayer(Game $game, Player $player, array $indexes): array
    {
        if (empty($indexes)) {
            throw new Exception("请选择要出的牌");
        }

        $indexes = array_unique($indexes);
        sort($indexes);

        $cards = [];
        foreach ($indexes as $index) {
            if (!isset($player->handCards[$index])) {
                throw new Exception("非法下标");
            }
            $cards[] = $player->handCards[$index];
        }

        $rule = Rule::analyze($cards);
        if ($rule->type == CardType::INVALID) {
            throw new Exception("牌型不合法");
        }

        $isWin = false;
        if ($game->lastPlay === null) {
            $isWin = true;
        } else {
            $isWin = Rule::compare($game->lastPlay, $rule);
        }

        if (!$isWin) {
            throw new Exception("牌型小于上一手出牌的人，不能出");
        }
        //大于上一手出牌的人，开始删牌
        rsort($indexes);//从大到小排序要删的索引
        foreach ($indexes as $index) {
            array_splice($player->handCards, $index, 1);
        }
        // 如果【当前玩家没有牌了】游戏结束
        if (count($player->handCards) === 0) {
            $game->gameOver = true;
            $game->winner = $game->currentPlayer;
        }

        $game->lastPlay = $rule;
        $game->lastPlayer = $game->currentPlayer;
        $game->gameRecord[] = new Action($game->currentPlayer, "play", $cards);
        $game->passCount = 0;
        $game->currentPlayer = ($game->currentPlayer + 1) % 3;

        return $cards;
    }
    public function pass(Game $game): void{
    // 第一手不能不要
    if ($game->lastPlay === null) {
        throw new Exception("第一手不能不要");
    }

    // 自己就是上一手出牌的人，不能 Pass
    if ($game->lastPlayer === $game->currentPlayer) {
        throw new Exception("你是本轮首家，不能不要");
    }

    // 连续 Pass +1
    $game->passCount++;
    // 保存游戏记录
    $game->gameRecord[] = new Action($game->currentPlayer, "pass", []);
    
    // 两家都不要
    if ($game->passCount >= 2) {
        // 保存最后出牌的人
        $lastPlayPlayer = $game->lastPlayer;
        
        // 新的一轮
        $game->lastPlay = null;
        $game->passCount = 0;

        // 最后出牌的人重新开始
        $game->currentPlayer = $lastPlayPlayer;
        return;
    }

        // 下一个玩家
        $game->currentPlayer = ($game->currentPlayer + 1) % 3;
    }
}