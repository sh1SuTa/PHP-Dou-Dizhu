<?php
class AIService
{
    // 统计手牌中每个值的索引
    private function countValues(Player $player): array
    {
        $map = [];

        foreach ($player->handCards as $index => $card) {
            $map[$card->value][] = $index;
        }

        ksort($map);

        return $map;
    }
    /**
     * 返回 AI 要出的手牌下标
     */
    public function think(Player $player, ?RuleResult $lastPlay): array
    {
        if ($lastPlay === null) {
            return $this->thinkFirstPlay($player);
        }
        switch ($lastPlay->type) {
        case CardType::SINGLE:
            return $this->findSingleGreater($player, $lastPlay->value);
        case CardType::PAIR:
            return $this->findPairGreater($player, $lastPlay->value);
        case CardType::TRIPLE:
            return $this->findTripleGreater($player, $lastPlay->value);
        case CardType::TRIPLE_WITH_ONE:
            return $this->findTripleOneGreater($player, $lastPlay->value);
        case CardType::BOMB:
            return $this->findBombGreater($player, $lastPlay->value);
        
        default:
            return [];
        }
        
        
    }
    
    private function findSingleGreater(Player $player, int $lastValue): array{
        foreach ($player->handCards as $index => $card) {
            if ($card->value > $lastValue) {
                return [$index];
            }
        }
        return [];
    }

    private function findPairGreater(Player $player, int $lastValue): array
    {
        $cards = $player->handCards;
        for ($i = 0; $i < count($cards); $i++) {
            for ($j = $i + 1; $j < count($cards); $j++) {
                if ($cards[$i]->value === $cards[$j]->value && $cards[$i]->value > $lastValue) {
                    return [$i, $j];
                }
            }
        }
        return [];
    }
    private function findTripleGreater(Player $player, int $lastValue): array{
        $map = $this->countValues($player);

        foreach ($map as $value => $indexes) {

            if ($value > $lastValue && count($indexes) >= 3) {

                return array_slice($indexes, 0, 3);

            }
        }

        return [];

    }
    private function findTripleOneGreater(Player $player, int $lastValue): array{
        $map = $this->countValues($player);
        ksort($map);

        foreach ($map as $value => $indexes) {

            if ($value > $lastValue && count($indexes) >= 3) {
                // 三张
                $result = array_slice($indexes, 0, 3);
                // 找一张单牌（不能是这组三张）
                foreach ($player->handCards as $index => $card) {
                    if ($card->value != $value) {
                        $result[] = $index;
                        return $result;
                    }
                }
            }
        }
        return [];
    }
    private function findBombGreater(Player $player, int $lastValue): array{
        $map = $this->countValues($player);

        foreach ($map as $value => $indexes) {

            if ($value > $lastValue && count($indexes) >= 4) {
                return array_slice($indexes, 0, 4);

            }
        }
        return [];
    }
    

    private function thinkFirstPlay(Player $player): array
{
    $pair = $this->findSmallestPair($player);
    if (!empty($pair)) {
        return $pair;
    }
    return $this->findSmallestSingle($player);
}

private function findSmallestSingle(Player $player): array
{
    $minIndex = 0;
    $minValue = $player->handCards[0]->value;
    
    foreach ($player->handCards as $index => $card) {
        if ($card->value < $minValue) {
            $minValue = $card->value;
            $minIndex = $index;
        }
    }
    
    return [$minIndex];
}

private function findSmallestPair(Player $player): array
{
    $cards = $player->handCards;
    $n = count($cards);
    $bestPair = null;
    $minValue = PHP_INT_MAX;
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = $i + 1; $j < $n; $j++) {
            if ($cards[$i]->value === $cards[$j]->value && $cards[$i]->value < $minValue) {
                $minValue = $cards[$i]->value;
                $bestPair = [$i, $j];
            }
        }
    }
    
    return $bestPair ?? [];
}
}