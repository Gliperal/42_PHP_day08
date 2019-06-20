<?php

include_once("Ship.class.php");

class GameMaster
{
	private $currentPlayer;
	private $ships;

	public function __construct()
	{
		$this->ships =
		[
			new Ship("Ship A", 0),
			new Ship("Ship B", 1)
		];
		$this->currentPlayer = 1;
		$this->finishTurn();
	}

	public function activateShip($name)
	{
		foreach ($this->ships as $ship)
			if ($ship->isActive())
				return ["error" => "You have an active ship. Finish its turn before selecting a new one."];
		foreach ($this->ships as $ship)
			if ($ship->getName() == $name)
			{
				if ($ship->isReady())
				{
					$ship->activate();
					return TRUE;
				}
				else
					return ["error" => "That ship does not belong to you."];
			}
		return ["error" => "You have no ship by that name!"];
	}

	public function finishTurn()
	{
		foreach ($this->ships as $ship)
			if ($ship->isReady() or $ship->isActive())
				return ["error" => "You must use all your ships before finishing a turn."];
		$this->currentPlayer = 1 - $this->currentPlayer;
		foreach ($this->ships as $ship)
			if ($ship->belongsTo($this->currentPlayer))
				$ship->ready();
		return TRUE;
	}

	public function getActiveShip()
	{
		foreach ($this->ships as $ship)
			if ($ship->isActive())
				return $ship;
		return FALSE;
	}
}

?>
