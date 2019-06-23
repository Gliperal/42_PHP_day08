<?php

include_once("GameMaster.class.php");
include_once("Ship.class.php");
include_once("ImperialIronclad.class.php");
require_once 'getGame.php';
session_start();

$game = getGame($_GET["lobby"]);
if (is_array($game))
	Console::log_error($game["error"]);
else
{
	foreach($game->getShips() as $ship)
		echo $ship->toHTML() . PHP_EOL;
	foreach($game->getObstacles() as $obstacle)
		echo $obstacle->toHTML() . PHP_EOL;
}

?>
