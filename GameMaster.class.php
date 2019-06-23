<?php

include_once("Ship.class.php");
include_once("ImperialIronclad.class.php"); // TODO This may become obsolete when the ships are not hard-coded
include_once("Obstacle.class.php");
include_once("Console.class.php");
include_once("validateUser.php");

class GameMaster
{
	private $_players;
	private $_currentPlayer;
	private $_ships;
	private $_obstacles;

	public function __construct($players)
	{
		Console::clear();
		$this->_players = $players;
		// TODO Construct more ships when there's more than 2 players.
		$this->_ships =
		[
			// Player 1 ships
			new ImperialIronclad("Annihilator", 0, ["x" => 20, "y" => 10], 0),
			new ImperialIronclad("B.O.B.", 0, ["x" => 10, "y" => 20], 270),
			// Player 2 ships
			new ImperialIronclad("Crusher", 1, ["x" => 130, "y" => 90], 180),
			new ImperialIronclad("Demolisher", 1, ["x" => 140, "y" => 80], 90)
		];
		$this->_obstacles =
		[
			new Obstacle(68, 42, 14, 16),
			new Obstacle(66, 40, 10, 1),
			new Obstacle(74, 59, 10, 1),
			new Obstacle(59, 55, 2, 6),
			new Obstacle(89, 39, 2, 6),
			new Obstacle(31, 73, 9, 3),
			new Obstacle(110, 24, 9, 3),
			new Obstacle(99, 64, 2, 2),
			new Obstacle(49, 34, 2, 2),
		];
		$this->_currentPlayer = count($_players) - 1;
		$this->finishTurn();
	}

	public function __destruct()
	{
	}

	public function doc()
	{
		return file_get_contents("GameMaster.doc.txt");
	}

	private function validate()
	{
		$playerName = $this->_players[$this->_currentPlayer];
		if (validateUser($playerName))
			return TRUE;
		else
		{
			Console::log_error("It is " . $playerName . "'s turn to move right now. If this is you, make sure you are logged in.");
			return FALSE;
		}
	}

	public function activateShip($name)
	{
		if (!$this->validate()) return ["error" => ""];
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
		Console::log_error("You have no ship by the name " . $name . "!");
		return ["error" => "You have no ship by that name!"];
	}

	public function getObstacles()
	{
		return $this->_obstacles;
	}

	public function getShips()
	{
		return $this->_ships;
	}

	public function getReadyShips()
	{
//		Console::log_debug("getReadyShips(): total ships " . count($this->_ships));
		$ships = array();
		foreach ($this->_ships as $ship)
		{
//			Console::log_debug($ship);
			if ($ship->isReady())
				$ships[] = $ship;
		}
		return $ships;
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

	private function checkForTurnEnd()
	{
		foreach ($this->_ships as $ship)
			if ($ship->isReady() or $ship->isActive())
				return;
		$this->finishTurn();
	}

	public function order($orders)
	{
		if (!$this->validate()) return ["error" => ""];
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
		if (!$this->validate()) return ["error" => ""];
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before moving it.");
			return ["error" => "You must activate a ship before moving it."];
		}
		$status = $ship->move($orders, $this->_ships, $this->_obstacles);
		if ($status == TRUE)
			$this->checkForTurnEnd();
		return $status;
	}

	public function attack()
	{
		if (!$this->validate()) return ["error" => ""];
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before attacking with it.");
			return ["error" => "You must activate a ship before attacking with it."];
		}
		return $ship->shoot($this->_ships, $this->_obstacles);
	}

	private function updateDeadShips()
	{
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
			$i++;
		}
	}

	public function winner()
	{
		$winner = FALSE;
		foreach ($this->_ships as $ship)
		{
			if ($winner === FALSE)
				$winner = $ship->getPlayer();
			else if (!$ship->belongsTo($winner))
				return FALSE;
		}
		return $this->_players[$winner];
	}

	private function finishTurn()
	{
		$this->updateDeadShips();
		$this->_currentPlayer = ($this->_currentPlayer + 1) % count($this->_players);
		foreach ($this->_ships as $ship)
			if ($ship->belongsTo($this->_currentPlayer))
				$ship->ready();
	}

	public function finishPhase()
	{
		if (!$this->validate()) return ["error" => ""];
		Console::log_debug("finishPhase()");
		$ship = $this->getActiveShip();
		if ($ship === FALSE)
		{
			Console::log_error("You must activate a ship before using it.");
			return ["error" => "You must activate a ship before using it."];
		}
		$status = $ship->finishPhase();
		if ($status == TRUE)
			$this->checkForTurnEnd();
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
