<?php

require "../bootstrap.php";

try {

    if (!isset($_SESSION["game"])) {
        Response::error(404, "游戏不存在");
    }

    /** @var Game $game */
    $game = unserialize($_SESSION["game"]);

    $service = new GameService();
    $aiService = new AIService();

    $service->pass($game);
    
    
    
    //轮到AI出牌
    while($game->currentPlayer>0){
        $indexes = $aiService->think($game->currentPlayer == 1 ? $game->ai1 : $game->ai2, $game->lastPlay);
        if (empty($indexes)) {
            $service->pass($game);
            continue;
        }
        $service->play($game, $indexes);
    }
    $_SESSION["game"] = serialize($game);

    Response::success([
        "player" => $game->player,
        "ai1Count" => count($game->ai1->handCards),
        "ai2Count" => count($game->ai2->handCards),
        "lastCards" => $game->lastPlay ? $game->lastPlay->cards : null,
        "lastPlayer" => $game->lastPlayer,
        "currentPlayer" => $game->currentPlayer,
        "gameOver" => $game->gameOver,
        "winner" => $game->winner,
        "gameRecord" => $game->gameRecord
    ]);

} catch (Exception $e) {

    Response::error(500, $e->getMessage());

}