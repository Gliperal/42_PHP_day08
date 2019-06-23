<?php

include_once("fileData.php");
include_once("Lobby.class.php");
include_once("GameMaster.class.php");

function getGame($lobbyID)
{
	if (load_data("lobbies.txt", $lobbies) === FALSE)
		return ["error" => "Failed to load server files."];
	foreach ($lobbies as $id=>$lobby)
		if ($id == $lobbyID)
		{
			$gm = $lobby->getGameMaster();
			$_SESSION["lobby_id"] = $lobbyID;
			$_SESSION["lobby_gm"] = $gm;
			return $gm;
		}
	return ["error" => "No lobby exists with that ID."];
}

function saveGame()
{
	if (load_data("lobbies.txt", $lobbies) === FALSE)
		return ["error" => "Failed to load server files."];
	foreach ($lobbies as $id=>$lobby)
		if ($id == $_SESSION["lobby_id"])
		{
			$status = $lobby->setGameMaster($_SESSION["lobby_gm"]);
			if ($status !== TRUE)
				return $status;
			if (save_data("lobbies.txt", $lobbies))
				return TRUE;
			else
				return ["error" => "Failed to save server files."];
		}
	return ["error" => "No lobby exists with that ID."];
}

?>
