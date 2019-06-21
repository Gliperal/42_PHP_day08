<?php
include_once("GameMaster.class.php");
include_once("Ship.class.php");
include_once("ImperialIronclad.class.php");
session_start();
?>

<html>
	<head>
		<style>
			body {
				height: 100%;
				width: 100%;
				background-size:	100% 100%;
				overflow:			hidden;
			}
			div.Aligner {
				display: flex;
				height: 100%;
				align-items: center;
				justify-content: center;
			}
			div.gameObjects
			{
				width: 100%; height: 100%; position: relative;
			}
			img.rotate90
			{
				-webkit-transform: rotate(90deg);
				-moz-transform: rotate(90deg);
				-o-transform: rotate(90deg);
				-ms-transform: rotate(90deg);
				transform: rotate(90deg);
			}
			img.rotate180
			{
				-webkit-transform: rotate(180deg);
				-moz-transform: rotate(180deg);
				-o-transform: rotate(180deg);
				-ms-transform: rotate(180deg);
				transform: rotate(180deg);
			}
			img.rotate270
			{
				-webkit-transform: rotate(270deg);
				-moz-transform: rotate(270deg);
				-o-transform: rotate(270deg);
				-ms-transform: rotate(270deg);
				transform: rotate(270deg);
			}
		</style>
		</style>
		</style>
	</head>
	<body background="resources/images/background.jpg">
		<div class="gameObjects">

<?php
$game = $_SESSION['master'];
foreach($game->_ships as $ship)
	echo $ship->toHTML() . PHP_EOL;
?>

		</div>
	</body>
</html>
