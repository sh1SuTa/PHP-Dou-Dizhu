<?php
class Rule
{
    private static function countValues(array $cards): array
    {
    $count = [];

        foreach ($cards as $card) {
            $count[$card->value] = ($count[$card->value] ?? 0) + 1;
        }
        // 按从小到大排序，方便判断顺子和连对，飞机
        ksort($count);
        return $count;
    }
    public static function analyze(array $cards): RuleResult
    {
        $count = count($cards);
        if ($count == 0) {
            return new RuleResult(
                CardType::INVALID,
                0,
                $cards
            );
        }
        // 单张
        if ($count == 1) {

            return new RuleResult(
                CardType::SINGLE,
                $cards[0]->value,
                $cards
            );

        }
        if($count == 2 ){
            // 对子
            if (   $cards[0]->value == $cards[1]->value) {

                return new RuleResult(
                    CardType::PAIR,
                    $cards[0]->value,
                    $cards
                );
            }//对王
            elseif($cards[0]->value ==16&&$cards[1]->value ==17){
                return new RuleResult(
                    CardType::JOKER_BOMB,
                    $cards[0]->value,
                    $cards
                );
            }
        }
        
        //三张牌
        if ($count == 3) {
            $values = self::countValues($cards);

            if (count($values) == 1) {
             $value = array_key_first($values);

             return new RuleResult(
                 CardType::TRIPLE,
                 $value,
                 $cards
             );
            }
        }
        // 三带1或者炸弹
        if ($count == 4) {
            $values = self::countValues($cards);
            //三带1
            if (count($values) == 2) {
                foreach ($values as $value => $times) {
                
                    if ($times == 3) {
                    
                        return new RuleResult(
                            CardType::TRIPLE_WITH_ONE,
                            $value,
                            $cards
                        );
                    }
                
                }
            
            }
            //炸弹
            else if (count($values) == 1) {
                $value = array_key_first($values);
                return new RuleResult(
                    CardType::BOMB,
                    $value,
                    $cards
                );
            }
        
        }
        //三带一对，
        if ($count == 5) {
            $values = self::countValues($cards);
            //三带一对
            if (count($values) == 2) {
                foreach ($values as $value => $times) {
                    if ($times == 3) {
                        return new RuleResult(
                            CardType::TRIPLE_WITH_ONE,
                            $value,
                            $cards
                        );
                    }
                }
            }
            
        }
        //四带二
        if ($count == 6) {
            $values = self::countValues($cards);
            if (count($values) == 2||count($values) == 3) {
                foreach ($values as $value => $times) {
                    if ($times == 4) {
                        return new RuleResult(
                            CardType::QUAD,
                            $value,
                            $cards
                        );
                    }
                }
            }
            
        }
        //顺子
        $values = self::countValues($cards);
        $isStraight = true;
        foreach ($values as $value => $times){
            if ($times != 1) {
                $isStraight = false;
                break;
            }
        }
        $points = array_keys($values);
        // 不能有2和大小王
        if (max($points) >= 15) {
            $isStraight = false;
        }
        // 必须连续
        for ($i = 1; $i < count($points); $i++) {
            if ($points[$i] != $points[$i - 1] + 1) {
                $isStraight = false;
                break;
            }
        }
        if ($isStraight) {
            // 是顺子
            return new RuleResult(
                CardType::STRAIGHT,
                end($points),          // 顺子最大牌，用于比较大小
                $cards,$count
            );
        }
        //连对
        $isPairPair = true;
        // 连对必须是2个对子
        foreach ($values as $value => $times){
            if ($times != 2) {
                $isPairPair = false;
                break;
            }
        }
        // 对子的牌必须连续
        for ($i = 1; $i < count($points); $i++) {
            if ($points[$i] != $points[$i - 1] + 1) {
                $isStraight = false;
                break;
            }
        }
        if ($isPairPair) {
            // 是连对
            return new RuleResult(
                CardType::PAIR_PAIR,
                end($points),          // 连对最大牌，用于比较大小
                $cards,$count
            );
        }
        // 飞机
        $map = self::countValues($cards);
        if (count($map) >= 2) {
            $isPlane = true;
            foreach ($map as $value => $times) {
                if ($times != 3) {
                    $isPlane = false;
                    break;
                }elseif($value>=15){
                    $isPlane = false;
                    break;
                }
            }
            if($isPlane){
                return new RuleResult(
                    CardType::PLANE,
                    end($points),
                    $cards,count($points)
                );
            }
            $isPlaneSingle=true;
            $plane = [];
            $planeSingle = [];
            foreach ($map as $value => $times) {
                if ($times == 3) {
                    //先取出3张相同的牌
                    $plane[] = $value;
                }
                if ($times == 1) {
                    $planeSingle[] = $value;
                }
            }
            if(count($planeSingle) != count($plane)){
                $isPlaneSingle = false;
            }
            if($isPlaneSingle){
                return new RuleResult(
                    CardType::PLANE_WITH_SINGLE,
                    end($plane),
                    $cards,count($plane)
                );
            }
            // 飞机带对子
            $isPlanePair = true;
            $planePair = [];
            foreach ($map as $value => $times) {
                if ($times == 2) {
                    $planePair[] = $value;
                }
            }
            if(count($planePair) != count($plane)){
                $isPlanePair = false;
            }
            if($isPlanePair){
                return new RuleResult(
                    CardType::PLANE_WITH_PAIR,
                    end($plane),
                    $cards,count($plane)
                );
            }
        }
        
        
        
        

        return new RuleResult(
            CardType::INVALID,
            0,
            $cards
        );
    }
    
    public static function compare(RuleResult $last, RuleResult $current): bool
    {
        // 上一手出牌是王炸
        if ($last->type == CardType::JOKER_BOMB) {
            return false;
        }
        //如果是王炸，直接返回 false
        if ($current->type == CardType::JOKER_BOMB) {
            return true;
        }
        //如果是炸弹
        if ($current->type == CardType::BOMB ) {
            //上一手出牌是炸弹，当前一手出牌是炸弹，判断炸弹的大小是否大于上一手出牌的炸弹的大小
            if ($last->type == CardType::BOMB) {
                return $current->value > $last->value;
            }//上一手出牌不是炸弹
            return true;
            
        }
        if ($last->type != $current->type&&$last->length != $current->length) {
            return false;
        }
        //类型长度相同，判断牌的大小是否大于上一手出牌的牌的大小
        return $current->value > $last->value;
    }
}