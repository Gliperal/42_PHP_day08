<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
$game = $_SESSION['master'];
$game->move(['type' => 'turn-right']);
header('Location: index.php');
?>
