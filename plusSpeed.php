<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
$game = $_SESSION['master'];
$game->order(['speed' => 1]);
header('Location: index.php');
?>