<?php

include_once("GameMaster.class.php");

class Lobby
{
	private $_size = 2;
	private $_players;
	private $_gameMaster;
	private $_locked = FALSE;

	public function __construct($host)
	{
		$this->_players = [$host];
	}

	public function addPlayer($player)
	{
		if ($this->_locked)
			return ["error" => "This game is full."];
		$this->_players[] = $player;
		if (count($this->_players) == $this->_size)
		{
			$this->_locked = TRUE;
			$this->_gameMaster = new GameMaster($this->_players);
		}
		return TRUE;
	}

	public function isReady()
	{
		return $this->_locked;
	}

	public function playersLeft()
	{
		return $this->_size - count($this->_players);
	}

	public function toHTML()
	{
		if ($this->_locked)
			$html = "<div class=\"lobby-closed\">";
		else
			$html = "<div class=\"lobby-open\">";
		$html .= "Lobby with players: ";
		for ($i = 0; $i < count($this->_players); $i++)
		{
			if ($i != 0)
				$html .= ", ";
			$html .= $this->_players[$i];
		}
		$html .= ".";
		if ($this->_locked)
			$html .= " Game in session. Click to view.";
		else
			$html .= " Awaiting " . $this->playersLeft() . " more. Click to join.";
		return $html . "</div>";
	}

	public function getGameMaster()
	{
		if ($this->_locked and $this->_gameMaster != null)
			return $this->_gameMaster;
		else
			return ["error" => "The game hasn't started yet!"];
	}

	// TODO This is an ugly hack solution and it should be burned as soon as possible
	public function setGameMaster($gm)
	{
		$this->_gameMaster = $gm;
	}
}

?>
