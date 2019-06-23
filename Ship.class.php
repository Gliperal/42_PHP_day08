<?php

include_once("dice.php");
include_once("escape.php");
include_once("Collidable.class.php");

// TODO Weapon locations won't update on collision with another ship
abstract class Ship extends Collidable
{
	private $_player;
	private $_name;
	private $_position;
	private $_angle;
	private $_hp;
	private $_pp;
	private $_cp; // Charge points (for weapons)
	private $_mp; // Movement points
	private $_untilTurn;
	private $_shield;
	private $_status;
		private const DEACTIVE = 0, READY = 1, ACTIVE = 2;
	private $_phase;
		private const ORDER = 0, MOVE = 1, SHOOT = 2;
	private $_stationary;

	protected abstract function getRawSize();
	protected abstract function getCenter();
	protected abstract function getMaxHP();
	protected abstract function getEP();
	protected abstract function getSpeed();
	protected abstract function getHandling();
	protected abstract function getBaseShield();
	protected abstract function getWeapons();

	public function __construct($name, $player, $position, $angle)
	{
		$this->_name = $name;
		$this->_player = $player;
		$this->_position = $position;
		$this->_angle = $angle;
		$this->_status = Ship::DEACTIVE;
		$this->_hp = $this->getMaxHP();
		$this->_shield = $this->getBaseShield();
		$this->_stationary = true;
	}

	public function __toString()
	{
		$loc = $this->getLocation();
		return sprintf("Ship[\"%s\" of %d (%d,%d) facing %d | status %d:%d | HP %d(%d) | PP %d | CP %d | MP %d ]",
			$this->_player,
			$this->_name,
			$loc["x"],
			$loc["y"],
			$this->_angle,
			$this->_status,
			$this->_phase,
			$this->_hp,
			$this->_shield,
			$this->_pp,
			$this->_cp,
			$this->_mp
		);
	}

	public function toTitleTag()
	{
		return sprintf("&#147%s&#148 | HP %d(%d) | PP %d | CP %d | MP %d",
			$this->_name,
			$this->_hp,
			$this->_shield,
			$this->_pp,
			$this->_cp,
			$this->_mp
		);
	}

	public function toHTML()
	{
		$size = $this->getSize();
		$loc = $this->getLocation();
		echo "<img";
		echo " src=\"/resources/images/ship_1_" . $this->_angle . ".png\"";
		echo " width=\"" . 100 * $size["x"] / 150 . "%\"";
		echo " height=\"" . 100 * $size["y"] / 100 . "%\"";
		echo " title=\"" . $this->toTitleTag() . "\"";
//		echo " class=\"rotate" . $this->_angle . "\"";
		echo " style=\"";
			echo "position: absolute;";
			echo "left: " . 100 * $loc["x"] / 150 . "%;";
			echo "top: " . 100 * $loc["y"] / 100 . "%;";
		echo "\"";
		echo " />";
	}

	public function belongsTo($player)
	{
		return $this->_player == $player;
	}

	public function getPlayer()
	{
		return $this->_player;
	}

	protected function getSize()
	{
		$raw = $this->getRawSize();
		if ($this->_angle % 180 == 90)
			return ["x" => $raw["y"], "y" => $raw["x"]];
		else
			return $raw;
	}

	protected function getLocation()
	{
		$loc = $this->_position;
		$size = $this->getRawSize();
		$center = $this->getCenter();
		switch($this->_angle)
		{
		case 0:
			return [
				"x" => $loc["x"] - $center["x"],
				"y" => $loc["y"] - $center["y"]
			];
		case 90:
			return [
				"x" => $loc["x"] - $center["y"],
				"y" => $loc["y"] + $center["x"] + 1 - $size["x"]
			];
		case 180:
			return [
				"x" => $loc["x"] + $center["x"] + 1 - $size["x"],
				"y" => $loc["y"] + $center["y"] + 1 - $size["y"]
			];
		case 270:
			return [
				"x" => $loc["x"] + $center["y"] + 1 - $size["y"],
				"y" => $loc["y"] - $center["x"]
			];
		}
	}

	public function getName()
	{
		return $this->_name;
	}

	public function isReady()
	{
		return $this->_status == Ship::READY;
	}

	public function isActive()
	{
		return $this->_status == Ship::ACTIVE;
	}

	public function isInactive()
	{
		return $this->_status == Ship::DEACTIVE;
	}

	public function ready()
	{
		$this->_status = Ship::READY;
	}

	public function getPP()
	{
		return $this->_pp;
	}

	public function activate()
	{
		$this->_status = Ship::ACTIVE;
		$this->_shield = $this->getBaseShield();
		$this->_cp = 0;
		foreach ($this->getWeapons() as $weapon)
			$weapon->resetCP();
		$this->_mp = $this->getSpeed();
		$this->_pp = $this->getEP();
		$this->_phase = Ship::ORDER;
		// TODO This should go at the end of a move_finalize function
		$this->debug_print_location();
		foreach ($this->getWeapons() as $weapon)
			$weapon->move($this->_position, $this->_angle);
	}

	private function repair($amount)
	{
		while ($amount > 0)
		{
			$roll = rollD6();
			Console::log_message("Rolling for repair... " . $roll . ".");
			if ($roll == 6)
			{
				$this->_hp = $this->getMaxHP();
				Console::log_message("Success! Ship hull points restored to " . $this->_hp . ".");
				return ;
			}
			$amount--;
		}
	}

	private function speedUp($amount)
	{
		if ($amount > 0)
		{
			Console::log_message("Rolling to boost speed...");
			$boost = rollManyD6($amount);
			$this->_mp += $boost;
			Console::log_message("Gained " . $boost . " more speed. Total speed for this turn is now " . $this->_mp . ".");
		}
	}

	public function order($orders)
	{
		if ($this->_status != Ship::ACTIVE)
		{
			Console::log_error("Your ship must be active to give it orders!");
			return ["error" => "Your ship must be active to give it orders!"];
		}
		if ($this->_phase != Ship::ORDER)
		{
			Console::log_error("Your ship must be in the order phase to give it orders!");
			return ["error" => "Your ship must be in the order phase to give it orders!"];
		}
		$total = 0;
		foreach (["speed", "shields", "weapons", "repairs"] as $key)
		{
			if (!array_key_exists($key, $orders))
				$orders[$key] = 0;
			$total += $orders[$key];
		}
		if ($total > $this->_pp)
		{
			Console::log_error("Your ship does not have that many PP to spend!");
			return ["error" => "Your ship does not have that many PP to spend!"];
		}
		if ($orders["repairs"] > 0)
		{
			if ($this->_stationary)
				$this->repair($orders["repairs"]);
			else
			{
				Console::log_error("A ship must be stationary in order to enact repairs!");
				return ["error" => "A ship must be stationary in order to enact repairs!"];
			}
		}
		$this->speedUp($orders["speed"]);
		$this->_shield += $orders["shields"];
		$this->_cp += $orders["weapons"];
		$this->_pp -= $total;
		return TRUE;
	}

	private function move_init()
	{
		if ($this->_stationary)
			$this->_untilTurn = 0;
		else
			$this->_untilTurn = $this->getHandling();
	}

	private function move_finalize()
	{
		$this->debug_print_location();
		foreach ($this->getWeapons() as $weapon)
			$weapon->move($this->_position, $this->_angle);
	}

	private function debug_print_location()
	{
		Console::log_debug(sprintf(
			"Ship moved to (%d, %d) angle %d (real position %d, %d)",
			$this->getLocation()["x"],
			$this->getLocation()["y"],
			$this->_angle,
			$this->_position["x"],
			$this->_position["y"]
		));
	}

	private function move_forward($ships, $obstacles)
	{
		$oldPosition = $this->_position;
		switch($this->_angle)
		{
		case 0:
			$this->_position["x"]++;
			break;
		case 90:
			$this->_position["y"]--;
			break;
		case 180:
			$this->_position["x"]--;
			break;
		case 270:
			$this->_position["y"]++;
			break;
		}
		if ($this->isOOB())
			return ["status" => "OOB"];
		foreach ($obstacles as $obstacle)
			if ($this->overlaps($obstacle))
				return ["status" => "OOB"];
		foreach ($ships as $ship)
		{
			if ($this != $ship and $this->overlaps($ship))
			{
				$this->_position = $oldPosition;
				return ["status" => "collision", "ship" => $ship];
			}
		}
		$this->_mp--;
		$this->_untilTurn--;
	}

	private function rotate($toAngle, $ships, $obstacles)
	{
		$oldAngle = $this->_angle;
		$this->_angle = $toAngle;
		if ($this->isOOB())
			return ["status" => "OOB"];
		foreach ($obstacles as $obstacle)
			if ($this->overlaps($obstacle))
				return ["status" => "OOB"];
		foreach ($ships as $ship)
		{
			if ($this != $ship and $this->overlaps($ship))
			{
				$this->_angle = $oldAngle;
				return ["status" => "collision", "ship" => $ship];
			}
		}
		$this->_untilTurn = $this->getHandling();
	}

	public function move($orders, $ships, $obstacles)
	{
		$this->move_init();
		if ($this->_status != Ship::ACTIVE)
		{
			Console::log_error("Your ship must be active to move!");
			return ["error" => "Your ship must be active to move!"];
		}
		if ($this->_phase != Ship::MOVE)
		{
			Console::log_error("Your ship must be in the movement phase to move!");
			return ["error" => "Your ship must be in the movement phase to move!"];
		}
		if (!array_key_exists("type", $orders))
		{
			Console::log_error("Your captain is drunk.");
			return ["error" => "Your captain is drunk."];
		}
		switch($orders["type"])
		{
		case "move":
			if (!array_key_exists("dist", $orders) or !is_numeric($orders["dist"]))
			{
				Console::log_error("Must specify a distance to move.");
				return ["error" => "Must specify a distance to move."];
			}
			if ($orders["dist"] > $this->_mp)
			{
				Console::log_error("I'm giving her all she's got Captain!");
				return ["error" => "I'm giving her all she's got Captain!"];
			}
			while ($orders["dist"] > 0)
			{
				$status = $this->move_forward($ships, $obstacles);
				if ($status["status"] != "OK")
					break ;
				$orders["dist"]--;
			}
			break;
		case "turn-left":
			if ($this->_untilTurn > 0)
			{
				Console::log_error("Your ship cannot make turns that sharp! Try moving a little first.");
				return ["error" => "Your ship cannot make turns that sharp! Try moving a little first."];
			}
			$status = $this->rotate(($this->_angle + 90) % 360, $ships, $obstacles);
			break;
		case "turn-right":
			if ($this->_untilTurn > 0)
			{
				Console::log_error("Your ship cannot make turns that sharp! Try moving a little first.");
				return ["error" => "Your ship cannot make turns that sharp! Try moving a little first."];
			}
			$status = $this->rotate(($this->_angle + 270) % 360, $ships, $obstacles);
			break;
		default:
			Console::log_error("'" . $orders["type"] . "' is not a valid movement type.");
			return ["error" => "'" . $orders["type"] . "' is not a valid movement type."];
		}
		if ($status["status"] == "OOB")
		{
			Console::log_message("Your ship strayed too close to the void and was destroyed!");
			$this->_hp = 0;
			$this->_status = Ship::DEACTIVE;
		}
		if ($status["status"] == "collision")
		{
			Console::log_message($this->_name . " collided with " . $status["ship"]->_name . "!");
			// TODO collide with $status["ship"]
			$this->_stationary = true;
			$this->_phase = Ship::SHOOT;
		}
		return TRUE;
	}

	public function shoot($ships, $obstacles)
	{
		if ($this->_status != Ship::ACTIVE)
		{
			Console::log_error("Your ship must be active to shoot!");
			return ["error" => "Your ship must be active to shoot!"];
		}
		if ($this->_phase != Ship::SHOOT)
		{
			Console::log_error("Your ship must be in the shooting phase to shoot!");
			return ["error" => "Your ship must be in the shooting phase to shoot!"];
		}
		// TODO Allow user to choose weapon and assign CP
		foreach ($this->getWeapons() as $weapon)
		{
			$weapon->receiveCP($this->_cp);
			$weapon->shoot($ships, $obstacles);
		}
		return TRUE;
	}

	public function finishPhase()
	{
		if ($this->_status != Ship::ACTIVE)
		{
			Console::log_error("You must have an active ship to do that!");
			return ["error" => "Your ship must be active to do that!"];
		}
		if ($this->_phase == Ship::SHOOT)
			$this->_status = Ship::DEACTIVE;
		else if ($this->_phase == Ship::MOVE)
		{
			// TODO Make sure the ship has travelled a distance at least equal to its handling if not stationary
			$this->move_finalize();
			$this->_phase = Ship::SHOOT;
		}
		else if ($this->_phase == Ship::ORDER)
			$this->_phase = Ship::MOVE;
		return TRUE;
	}

	public function isDead()
	{
		return $this->_hp <= 0;
	}

	public function takeDamage()
	{
		if ($this->isDead())
			return ;
		if ($this->_shield > 0)
		{
			Console::log_message($this->_name . " took 1 point of damage to the shield.");
			$this->_shield--;
		}
		else
		{
			Console::log_message($this->_name . " took 1 point of damage to the hull!");
			$this->_hp--;
		}
		if ($this->_hp <= 0)
			Console::log_message($this->_name . " was destroyed!");
	}
}

?>
