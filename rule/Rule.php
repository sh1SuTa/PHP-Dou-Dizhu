<?php
class Rule
{
    public static function analyze(array $cards): RuleResult
    {
        $count = count($cards);

        // 单张
        if ($count == 1) {

            return new RuleResult(
                CardType::SINGLE,
                $cards[0]->value,
                $cards
            );

        }

        // 对子
        if (
            $count == 2 &&
            $cards[0]->value == $cards[1]->value
        ) {

            return new RuleResult(
                CardType::PAIR,
                $cards[0]->value,
                $cards
            );

        }

        return new RuleResult(
            CardType::INVALID,
            0,
            $cards
        );
    }
    
    public static function compare(RuleResult $last, RuleResult $current): bool
    {
        if ($last->type != $current->type) {
            return false;
        }

        return $current->value > $last->value;
    }
}