<?php
class AIService
{
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