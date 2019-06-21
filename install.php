<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
$game = new GameMaster();
$master = 'master';
$_SESSION[$master] = $game;
header('Location: index.php');
?>
