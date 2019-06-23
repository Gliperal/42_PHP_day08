<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
require_once 'Console.class.php';
require_once 'getGame.php';
session_start();
Console::log_debug("activate ship by name '" . $_POST['name'] . "'");
$game = getGame($_POST["lobby"]);
if (is_array($game))
	Console::log_error($game["error"]);
else
	$game->activateShip($_POST['name']);
saveGame();
?>
