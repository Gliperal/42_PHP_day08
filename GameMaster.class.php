<?php

include_once("Ship.class.php");
include_once("ImperialIronclad.class.php"); // TODO This may become obsolete when the ships are not hard-coded

class GameMaster
{
	private $currentPlayer;
	private $ships;

	public function __construct()
	{
		$this->ships =
		[
			new ImperialIronclad("Ship A", 0, 180),
			new ImperialIronclad("Ship B", 1, 90)
		];
		$this->currentPlayer = 1;
		$this->finishTurn();
	}

	public function __destruct()
	{
	}

	public function doc()
	{
		return file_get_contents("GameMaster.doc.txt");
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

	private function getActiveShip()
	{
		foreach ($this->ships as $ship)
			if ($ship->isActive())
				return $ship;
		return FALSE;
	}

	/*
	public function displayActiveShip()
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			echo "FALSE" . PHP_EOL;
		else
			print_r($ship);
	}
	*/

	public function order($orders)
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			return ["error" => "You must activate a ship before commanding it."];
		return $ship->order($orders);
	}

	public function move($orders)
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			return ["error" => "You must activate a ship before moving it."];
		return $ship->move($orders);
	}

	public function attack()
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			return ["error" => "You must activate a ship before attacking with it."];
		return $ship->shoot();
	}

	public function finishPhase()
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			return ["error" => "You must activate a ship before using it."];
		return $ship->finishPhase();
	}
}

?>
