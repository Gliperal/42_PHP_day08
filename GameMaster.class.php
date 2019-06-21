<?php

include_once("Ship.class.php");
include_once("ImperialIronclad.class.php"); // TODO This may become obsolete when the ships are not hard-coded
include_once("Obstacle.class.php");

class GameMaster
{
	private $_currentPlayer;
	public $_ships;
	private $_obstacles;

	public function __construct()
	{
		$this->_ships =
		[
			new ImperialIronclad("Ship A", 0, ["x" => 10, "y" => 10], 0),
			new ImperialIronclad("Ship B", 1, ["x" => 30, "y" => 30], 90)
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

	public function getActiveShip()
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

	private function finishTurn()
	{
		$this->_currentPlayer = 1 - $this->_currentPlayer;
		$allShipsDestroyed = TRUE;
		$i = 0;
		while ($i < count($this->_ships))
		{
			$ship = $this->_ships[$i];
			if ($ship->isDead())
			{
				// TODO Dead ships become obstacles
				echo "Removing ship from playfield..." . PHP_EOL;
				array_splice($this->_ships, $i, 1);
				continue;
			}
			if ($ship->belongsTo($this->_currentPlayer))
			{
				$allShipsDestroyed = FALSE;
				$ship->ready();
			}
			$i++;
		}
		if ($allShipsDestroyed)
			// TODO
			echo "You win!" . PHP_EOL;
	}

	public function finishPhase()
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
			return ["error" => "You must activate a ship before using it."];
		$status = $ship->finishPhase();
		if ($status == TRUE)
		{
			foreach ($this->_ships as $ship)
				if ($ship->isReady() or $ship->isActive())
					return TRUE;
			$this->finishTurn();
		}
		else
			return $status;
	}

	public function __toString()
	{
		$str = "GameMaster[" . PHP_EOL;
		$str .= "Player " . $this->_currentPlayer . " to move." . PHP_EOL;
		foreach ($this->_ships as $ship)
			$str .= $ship . PHP_EOL;
		foreach ($this->_obstacles as $obstacle)
			$str .= $obstacle . PHP_EOL;
		$str .= "]";
		return $str;
	}
}

?>
