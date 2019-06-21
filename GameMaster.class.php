<?php

include_once("Ship.class.php");
include_once("ImperialIronclad.class.php"); // TODO This may become obsolete when the ships are not hard-coded
include_once("Obstacle.class.php");

class GameMaster
{
	private $_currentPlayer;
	private $_ships;
	private $_obstacles;

	public function __construct()
	{
		$this->_ships =
		[
			new ImperialIronclad("Ship A", 0, ["x" => 10, "y" => 10], 0),
			new ImperialIronclad("Ship B", 1, ["x" => 30, "y" => 50], 90)
		];
		$this->_obstacles =
		[
			new Obstacle(70, 45, 10, 10)
		];
		$this->_currentPlayer = 1;
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
		foreach ($this->_ships as $ship)
			if ($ship->isActive())
				return ["error" => "You have an active ship. Finish its turn before selecting a new one."];
		foreach ($this->_ships as $ship)
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
		foreach ($this->_ships as $ship)
			if ($ship->isReady() or $ship->isActive())
				return ["error" => "You must use all your ships before finishing a turn."];
		$this->_currentPlayer = 1 - $this->_currentPlayer;
		foreach ($this->_ships as $ship)
			if ($ship->belongsTo($this->_currentPlayer))
				$ship->ready();
		return TRUE;
	}

	private function getActiveShip()
	{
		foreach ($this->_ships as $ship)
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
		return $ship->shoot($this->_ships, $this->_obstacles);
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
