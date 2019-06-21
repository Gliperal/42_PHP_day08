<?php

include_once("Ship.class.php");
include_once("ImperialIronclad.class.php"); // TODO This may become obsolete when the ships are not hard-coded
include_once("Obstacle.class.php");
include_once("Console.class.php");

class GameMaster
{
	private $_currentPlayer;
	public $_ships;
	private $_obstacles;

	public function __construct()
	{
		Console::clear();
		$this->_ships =
		[
			new ImperialIronclad("Bob", 0, ["x" => 10, "y" => 10], 0),
			new ImperialIronclad("Steve", 1, ["x" => 30, "y" => 30], 90)
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
			{
				Console::log_error("You have an active ship. Finish its turn before selecting a new one.");
				return ["error" => "You have an active ship. Finish its turn before selecting a new one."];
			}
		foreach ($this->_ships as $ship)
			if ($ship->getName() == $name)
			{
				if ($ship->isReady())
				{
					$ship->activate();
					return TRUE;
				}
				else
				{
					Console::log_error("That ship does not belong to you.");
					return ["error" => "That ship does not belong to you."];
				}
			}
		Console::log_error("You have no ship by that name!");
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
		{
			Console::log_error("You must activate a ship before commanding it.");
			return ["error" => "You must activate a ship before commanding it."];
		}
		return $ship->order($orders);
	}

	public function move($orders)
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before moving it.");
			return ["error" => "You must activate a ship before moving it."];
		}
		return $ship->move($orders);
	}

	public function attack()
	{
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before attacking with it.");
			return ["error" => "You must activate a ship before attacking with it."];
		}
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
				Console::log_debug("Removing ship from playfield...");
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
			Console::log_message("You win!");
	}

	public function finishPhase()
	{
		Console::log_debug("finishPhase()");
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before using it.");
			return ["error" => "You must activate a ship before using it."];
		}
		$status = $ship->finishPhase();
		if ($status == TRUE)
		{
			foreach ($this->_ships as $ship)
				if ($ship->isReady() or $ship->isActive())
					return TRUE;
			$this->finishTurn();
		}
		else
			Console::log_error($status["error"]);
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
