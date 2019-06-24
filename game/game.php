<?php
require_once 'GameMaster.class.php';
require_once 'Ship.class.php';
require_once 'ImperialIronclad.class.php';
session_start();
?>
<html>
	<head>
		<title>Awesome Battleships Battles</title>
		<style>
			body {
				min-height: 100%;
				min-width: 100%;
				margin: 0px;
				background-color: black;
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
				border-color:		#888;
				padding:			5px;
			}
			div.small {
				display: flex;
				width:	10%;
				background-color:	black;
				border-style:		solid;
				border-color:		#888;
				padding:			5px;
			}
			div.end {
				display:			flex-wrap;
				overflow:			hidden;
				width:				auto;
				background-color:	black;
				border-style:		solid;
				border-color:		#888;
				padding:			5px;
			}
			p.log-msg {
				color:				white;
			}
			p.log-err {
				color:				red;
			}
			div.Align-Bottom {
				display: flex;
				width:		100%;
				justify-content: flex-start;
			}
			div.Iframe {
				width: 100%;
				height: 80%;
			}
			div.gameObjects
			{
				width: 100%;
				height: 100%;
				position: relative;
				/* background-image: url("resources/images/background.jpg"); */
				background-image: url("/resources/images/space_background.png");
				background-size: 100% 100%;
			}
			iframe {
				height: 100%;
				width: 100%;
				border: on;
			}
			form.radio {
				color: white;
			}
			.orders {
				color: white;
			}
			img.pl {
				height:	62px;
				width:	200px;
			}
			img.fire {
				width:	100%;
				height:	30%;
			}
		</style>
		<script
			src="https://code.jquery.com/jquery-3.4.1.min.js"
			integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			crossorigin="anonymous">
		</script>
		<script src="game.js"></script>
	</head>
	<body>
		<div class="Iframe">
			<div class="gameObjects" id="gameObjects">
			</div>
		</div>
		<div class="Align-Bottom">
			<div class="normal">
				<div id="readyShips">
				</div>
			</div>
			<div class="normal">
				<div class="orders">
					<button style=" border: none;background-color: Transparent" onclick='sendGet("plusSpeed.php")'><img class="pl" src="/resources/images/speed_icon.png" /></button>
					<br>
					<br>
					<button style=" border: none;background-color: Transparent" onclick='sendGet("plusWeapon.php")'><img class="pl" src="/resources/images/weapon_icon.png" /></button>
					<br>
					<br>
					<button style=" border: none;background-color: Transparent" onclick='sendGet("plusShield.php")'><img class="pl" src="/resources/images/shield_icon.png" /></button>
					<br>
					<br>
					<button style=" border: none;background-color: Transparent" onclick='sendGet("plusRepair.php")'><img class="pl" src="/resources/images/repair_icon.png" /></button>
				</div>
<!--?php
$currentShip = $game->getActiveShip();
if ($currentShip != False)
{
	$pp = $currentShip->getPP();
	echo "<p><font color='white'>PP: ".$pp."</font></p>";
}
?-->
			</div>
			<div class="normal">
				<button onclick='sendGet("moveOrderLeft.php")'><img class="pl" src="/resources/images/turn_left.png" /></button><br/><br/>
				<button onclick='sendGet("moveOrderRight.php")'><img class="pl" src="/resources/images/turn_right.png" /></button><br/><br/>
				<button onclick='sendGet("moveOrderForward.php")'>Forward</button>
			</div>
			<div class="normal">
				<button onclick='sendGet("attack.php")'><img class="fire" src="/resources/images/fire.png" /></button>
			</div>
			<div class="small">
				<div>
					<button onclick='sendGet("endPhase.php")'>End Phase</button>
				</div>
			</div>
			<div class="end" id="log">
			</div>
		</div>
	</body>
</html>
