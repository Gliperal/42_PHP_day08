<html>
	<head>
		<title>Lobby</title>
		<style>


div.wait
{
	font-size: 16pt;
	text-align: center;
	width: 100%;
}

p.doggo
{
	text-align: center;
}


		</style>
		<script
			src="https://code.jquery.com/jquery-3.4.1.min.js"
			integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			crossorigin="anonymous">
		</script>
		<script>

$(document).ready(function() {
	var queryString = window.location.search;
	if(queryString.startsWith("?"))
		queryString = queryString.substring(1);
	var query = queryString.split(";");
	for(var i in query)
	{
		var pair = query[i].split("=");
		if (pair[0] == "id")
		{
			var source = new EventSource("lobbyAlert.php?id=" + pair[1]);
			source.onmessage = function(event)
			{
				if (event.data == "OK")
					location.reload();
			};
		}
	}
});

		</script>
	</head>
	<body>



<?php
include_once("fileData.php");
include_once("errorPage.php");
include_once("Lobby.class.php");

session_start();

if (!array_key_exists("id", $_GET))
	exit("Bad request.");
if (load_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to load server files."));
$lobbyID = $_GET["id"];	
$foundLobby = FALSE;
foreach ($lobbies as $id=>$lobby)
	if ($id == $lobbyID)
	{
		$foundLobby = TRUE;
		break;
	}
if (!$foundLobby)
	exit(errorPage("No lobby exists with that ID."));
$awaiting = $lobby->playersLeft();
if ($awaiting === 0)
	header("Location:game.php?id=" . $lobbyID);
else if ($awaiting === 1)
	echo "<div class=\"wait\">Waiting for 1 more player to join...</div><br/>";
else
	echo "<div class=\"wait\">Waiting for " . $lobby->playersLeft() . " more players to join...</div><br/>";
echo "<div class=\"wait\">In the mean time, enjoy this dog:</div>";
echo "<p class=\"doggo\"><img src=\"/resources/images/dog.jpg\" alt=\"Nevermind, the dog didn't load.\" /></p>";
?>



	</body>
</html>
