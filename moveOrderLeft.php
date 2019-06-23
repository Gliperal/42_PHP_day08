<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
require_once 'getGame.php';
session_start();
$game = getGame($_GET["lobby"]);
if (is_array($game))
	Console::log_error($game["error"]);
else
	$game->move(['type' => 'turn-left']);
saveGame();
?>
