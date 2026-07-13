<?php

require "../bootstrap.php";

$deck = new Deck();
// 洗牌
$deck->shuffle();

$game = new Game();

$game->player = new Player("玩家", false);
$game->ai1 = new Player("AI1", true);
$game->ai2 = new Player("AI2", true);

for ($i = 0; $i < 17; $i++) {

    $game->player->addCard($deck->draw());
    $game->ai1->addCard($deck->draw());
    $game->ai2->addCard($deck->draw());

}

$game->bottomCards = $deck->cards;
//先默认玩家是地主
for($i = 0; $i < 3; $i++)
{
    $game->player->addCard($deck->draw());
}



$game->player->sortCards();
$game->ai1->sortCards();
$game->ai2->sortCards();

$_SESSION["game"] = serialize($game);

header("Content-Type: application/json");

echo json_encode([
    "code" => 200,
    "msg" => "success"
]);