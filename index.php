<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
if (!array_key_exists('master', $_SESSION))
	header('Location: install.php');
?>
<html>
	<head>
		<style>
			body {
				min-height: 100%;
				min-width: 100%;
			}
			div.Aligner {
				display: flex;
				min-height: 100%;
				align-items: center;
				justify-content: center;
			}
			div.normal {
				display: flex-wrap;
				width:	15%;
				background-color:	black;
				border-style:		solid;
				border-color:		white;
			}
			div.small {
				display: flex;
				width:	10%;
				background-color:	black;
				border-style:		solid;
				border-color:		white;
			}
			div.end {
				display: flex;
				width:	30%;
				background-color:	black;
				border-style:		solid;
				border-color:		white;
			}
			div.Align-Bottom {
				display: flex;
				min-height: 20%;
				width:		10%
				align-items: left;
				justify-content: flex-start;
			}
			div.Iframe {
				display: flex;
				overflow: auto;
				min-height: 80%;
				max-height: 80%;
				width: 100%;
			}
			iframe {
				height: = 100%;
				width: 100%;
				border: on;
			}
			form.radio {
				color: white;
			}
			form.orders {
				color: white;
			}
			img.pl {
				height:	20px;
				width:	20px;
			}
		</style>
	</head>
	<body>
		<div class="Iframe">
			<iframe src="map.html"></iframe>
		</div>
		<div class="Align-Bottom">
			<div class="normal">
				<form class="radio" action="active.php" method="post">
<?php
$game = $_SESSION['master'];
foreach($game->_ships as $ship)
{
	$name = $ship->getName();
	if ($ship instanceof ImperialIronclad)
		$type = 'Imperial Ironclad';
	echo '<input type="radio" name="name" value='.$name.'>'.$type.' '.$name.'</input>';
}
?>
					<input type="submit"></input>
				</form></div>
			<div class="normal">
			<form class="orders" action="plusSpeed.php" method="get">
				<p>Speed:</p>
				<button><img class="pl" src="resources/images/plus_button.png" /></button>
			</form>
			<form class="orders" action="plusWeapon.php" method="get">
				<p>Weapon:</p>
				<button><img class="pl" src="resources/images/plus_button.png" /></button>
			</form>
			<form class="orders" action="plusShield.php" method="get">
				<p>Shield:</p>
				<button><img class="pl" src="resources/images/plus_button.png" /></button>
			</form>
			<form class="orders" action="plusRepair.php" method="get">
				<p>Repair:</p>
				<button><img class="pl" src="resources/images/plus_button.png" /></button>
			</form>
<?php
$currentShip = $game->getActiveShip();
if ($currentShip != False)
{
	$pp = $currentShip->getPP();
	echo "<p><font color='white'>PP: ".$pp."</font></p>";
}
?>
			</div>
			<div class="normal">
				<form class="move" action="moveOrderLeft.php", method="get">
					<button><img class="pl" src="resources/images/turn_left.png" /></button>
				</form>
				<form class="move" action="moveOrderRight.php", method="get">
					<button><img class="pl" src="resources/images/turn_right.png" /></button>
				</form>
			</div>
			<div class="normal"></div>
			<div class="small"></div>
			<div class="end"></div>
		</div>
	</body>
</html>