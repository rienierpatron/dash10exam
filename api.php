<?php
include('vendor/autoload.php');

use App\Request;
use App\Controllers\PlayerController;

$request = new Request();

$player = new PlayerController();

$process = $request->query('process');

if ($process == "list") {
    $id = ($request->query('game_type') == 'rugby') ? 1 : 2;
    $game_type = $request->query('game_type');
    $list = $player->list($id, $game_type);

    echo $list;
} else if ($process == "player") {
    $id = (isset($_GET['id'])) ? $request->query('id') : 2;
    $game_type = $request->query('game_type');
    $list = $player->player_info($id, $game_type);

    echo $list;
}