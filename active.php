<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
print_r($_POST);
$game = $_SESSION['master'];
$game->activateShip($_POST['name']);
header('Location: index.php');
?>
