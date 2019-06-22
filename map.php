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
		</style>
		</style>
		</style>
	</head>
	<body background="resources/images/background.jpg">
		<div class="gameObjects">

<?php
$game = $_SESSION['master'];
foreach($game->getShips() as $ship)
	echo $ship->toHTML() . PHP_EOL;
foreach($game->getObstacles() as $obstacle)
	echo $obstacle->toHTML() . PHP_EOL;
?>

		</div>
	</body>
</html>
