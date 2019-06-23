<?php

include_once("fileData.php");
include_once("errorPage.php");
include_once("Lobby.class.php");

header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");

function check()
{
	if (!array_key_exists("id", $_GET))
		return FALSE;
	if (load_data("lobbies.txt", $lobbies) === FALSE)
		return FALSE;
	$lobbyID = $_GET["id"];
	$ready = FALSE;
	foreach ($lobbies as $id=>$lobby)
		if ($id == $lobbyID)
			return $lobby->isReady();
	return FALSE;
}

if (check())
	echo "data: OK\n\n";
else
	echo "data: KO\n\n";
flush();

?>
