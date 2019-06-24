<?php

include_once("../whoami.php");
include_once("errorPage.php");
include_once("fileData.php");
include_once("Lobby.class.php");

session_start();

$user = whoami();
if ($user === FALSE)
	exit(errorPage("You must be logged in to do that!"));
if (load_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to load server files."));
while (true)
{
	$lobbyID = bin2hex(random_bytes(3));
	$lobby = new Lobby($user);
	foreach ($lobbies as $id=>$zxcde)
		if ($id == $lobbyID)
			continue;
	$lobbies[$lobbyID] = $lobby;
	break;
}
if (save_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to save server files."));
header("Location:lobby.php?id=" . $lobbyID);

?>
