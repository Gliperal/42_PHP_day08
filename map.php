<?php

include_once("GameMaster.class.php");
include_once("Ship.class.php");
include_once("ImperialIronclad.class.php");
session_start();

$game = $_SESSION['master'];
foreach($game->getShips() as $ship)
	echo $ship->toHTML() . PHP_EOL;
foreach($game->getObstacles() as $obstacle)
	echo $obstacle->toHTML() . PHP_EOL;

?>
