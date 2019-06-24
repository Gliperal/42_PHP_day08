<html>
	<head>
		<title>Lobbies</title>
			<style>
				body
				{
					background-size: 100% 100%;
					height: 100%;
					margin: 0;
					background-repeat: no-repeat;
					background-attachment: fixed;
					background-image: linear-gradient(to bottom, #000066 -2%, #9900cc 101%);
				}

				div.lobby-open, div.lobby-closed, div.lobby-over, div.new
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
					background: rgba(187, 221, 187, 0.7); #BDB;
					color: black;
				}

				div.lobby-closed
				{
					background: rgba(221, 221, 187, 0.7); #BDB;
					color: black;
				}

				div.lobby-over
				{
					background: rgba(221, 187, 187, 0.7); #BDB;
					color: black;
				}

				div.new
				{
					margin-top: 20px;
					margin-left: auto;
					margin-right: auto;
					max-width: 200px;
					color: black;
					background: rgba(136, 136, 221, 0.7); #BDB;
					background-color: #88D;
				}

				a.lobby-link
				{
					text-decoration: none;
				}
				.topnav {
				  overflow: hidden;
				  background-color: rgb(0, 0, 0);
				}
				
				.topnav a {
				  float: left;
				  color: #f2f2f2;
				  text-align: center;
				  padding: 14px 16px;
				  text-decoration: none;
				  font-size: 17px;
				}
				
				.topnav a:hover {
					background-color: #ddd;
  					color: black;
				}
				
				.topnav a.active {
				  background-color: rgb(255, 0, 221);
				  color: white;
				}
		</style>
	</head>
	<body>
		<div class="topnav">
			<a href="../homepage.html">Home</a>
			<a class="active" href="lobbies.php">Lobbies</a>
			<a href="rankings.php">Ranks</a>
			<a href="">About</a>
		</div>
<?php

include_once("fileData.php");
include_once("errorPage.php");
include_once("Lobby.class.php");

if (load_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to load server files."));
foreach ($lobbies as $id=>$lobby)
{
	if ($lobby->isOver())
		echo $lobby->toHTML() . "<br/>";
	else
	{
		if ($lobby->isReady())
			$href = "game.php?id=" . $id;
		else
			$href = "joinLobby.php?id=" . $id;
		echo "<a class=\"lobby-link\" href=\"" . $href . "\">" . $lobby->toHTML() . "</a><br/>";
	}
}

?>
		<div style="display: block;margin-left: auto;margin-right: auto">
			<a class="lobby-link" href="createLobby.php">
				<img src="../resources/new_lobby_icon.png">
			</a>
		</div>
		<iframe src="/game/chatroom.php" />
	</body>
</html>
