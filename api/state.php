<?php

require "../bootstrap.php";

header("Content-Type: application/json");

if (!isset($_SESSION["game"])) {

    echo json_encode([
        "code" => 404,
        "msg" => "游戏不存在"
    ]);

    exit;
}

$game = unserialize($_SESSION["game"]);

echo json_encode([
    "code" => 200,

    "data" => [

        "player" => $game->player,

        "ai1Count" => count($game->ai1->handCards),

        "ai2Count" => count($game->ai2->handCards),

        "bottom" => $game->bottomCards,

        "lastCards" => $game->lastPlay ? $game->lastPlay->cards : null,

        "lastPlayer" => $game->lastPlayer,

        "currentPlayer" => $game->currentPlayer,

        "gameOver" => $game->gameOver,

        "winner" => $game->winner,

        "gameRecord" => $game->gameRecord

    ]

]);