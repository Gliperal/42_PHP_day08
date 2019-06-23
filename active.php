<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
require_once 'Console.class.php';
session_start();
Console::log_debug("activate ship by name '" . $_POST['name'] . "'");
$game = $_SESSION['master'];
$game->activateShip($_POST['name']);
?>
