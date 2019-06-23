<html>
	<head>
		<title>Lobbies</title>
		<style>


div.lobby-open, div.lobby-closed, div.new
{
	border: 2px solid black;
	border-radius: 2px;
	padding: 5px;
	text-align: center;
	font-size: 16pt;
	font-family: Arial, Helvetica, sans-serif;
}

div.lobby-open
{
	background-color: #BDB;
	color: black;
}

div.lobby-closed
{
	background-color: #DBB;
	color: black;
}

div.new
{
	margin-top: 20px;
	margin-left: auto;
	margin-right: auto;
	max-width: 200px;
	color: black;
	background-color: #88D;
}

a.lobby-link
{
	text-decoration: none;
}


		</style>
	</head>
	<body>


<?php

include_once("fileData.php");
include_once("errorPage.php");
include_once("Lobby.class.php");

if (load_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to load server files."));
foreach ($lobbies as $id=>$lobby)
{
	if ($lobby->isReady())
		$href = "game.php?id=" . $id;
	else
		$href = "joinLobby.php?id=" . $id;
	echo "<a class=\"lobby-link\" href=\"" . $href . "\">" . $lobby->toHTML() . "</a><br/>";
}

?>


		<a class="lobby-link" href="createLobby.php"><div class="new">New Lobby</div></a>
	</body>
</html>
