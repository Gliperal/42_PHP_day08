<?php

include_once("whoami.php");
include_once("fileData.php");
include_once("errorPage.php");
include_once("Lobby.class.php");

session_start();

if (!array_key_exists("id", $_GET))
	exit("Bad request.");
$user = whoami();
if ($user === FALSE)
	exit(errorPage("You must be logged in to do that!"));
if (load_data("lobbies.txt", $lobbies) === FALSE)
	exit(errorPage("Failed to load server files."));
$lobbyID = $_GET["id"];	
foreach ($lobbies as $id=>$lobby)
	if ($id == $lobbyID)
	{
		$status = $lobby->addPlayer($user);
		if ($status !== TRUE)
			exit(errorPage($status["error"]));
		if (save_data("lobbies.txt", $lobbies) === FALSE)
			exit(errorPage("Failed to save server files."));
		header("Location:lobby.php?id=" . $lobbyID);
		exit("OK");
	}
exit(errorPage("No lobby exists with that ID."));

?>
