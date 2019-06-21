<?php

include_once("dice.php");

abstract class Ship
{
	// To be implemented by child class:
	protected $_ep;
	protected $_speed;
	protected $_handling;
	protected $_base_shield;
	protected $_weapons;

	private $_player;
	private $_name;
	private $_position = ["x" => 0, "y" => 0];
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

	protected abstract function getSize();
	protected abstract function getMaxHP();

	public function __construct($name, $player, $angle)
	{
		$this->_name = $name;
		$this->_player = $player;
		$this->_angle = $angle;
		$this->_status = Ship::DEACTIVE;
		$this->_hp = $this->getMaxHP();
		$this->_stationary = true;
	}

	public function belongsTo($player)
	{
		return $this->_player == $player;
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

	public function ready()
	{
		$this->_status = Ship::READY;
	}

	public function activate()
	{
		$this->_status = Ship::ACTIVE;
		$this->_shield = $this->_base_shield;
		$this->_cp = 0;
		$this->_mp = $this->_speed;
		$this->_pp = $this->_ep;
		$this->_phase = Ship::ORDER;
	}

	private function repair($amount)
	{
		while ($amount > 0)
		{
			$roll = rollD6();
			echo "Rolling for repair... " . $roll . "." . PHP_EOL;
			if ($roll == 6)
			{
				$this->_hp = $this->getMaxHP();
				echo "Success! Ship hull points restored to " . $this->_hp . "." . PHP_EOL;
				return ;
			}
			$amount--;
		}
	}

	private function speedUp($amount)
	{
		if ($amount > 0)
		{
			echo "Rolling to boost speed..." . PHP_EOL;
			$boost = rollManyD6($amount);
			$this->_mp += $boost;
			echo "Gained " . $boost . " more speed. Total speed for this turn is now " . $this->_mp . "." . PHP_EOL;
		}
	}

	public function order($orders)
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to give it orders!"];
		if ($this->_phase != Ship::ORDER)
			return ["error" => "Your ship must be in the order phase to give it orders!"];
		$total = 0;
		foreach (["speed", "shields", "weapons", "repairs"] as $key)
		{
			if (!array_key_exists($key, $orders))
				$orders[$key] = 0;
			$total += $orders[$key];
		}
		if ($total > $this->_pp)
			return ["error" => "Your ship does not have that many PP to spend!"];
		if ($orders["repairs"] > 0)
		{
			if ($this->_stationary)
				$this->repair($orders["repairs"]);
			else
				return ["error" => "A ship must be stationary in order to enact repairs!"];
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
			$this->_untilTurn = $this->_handling;
	}

	private function move_forward($dist)
	{
		$mov = array();
		switch($this->_angle)
		{
		case 0:
			$mov["x"] = $dist;
			$mov["y"] = 0;
			break;
		case 90:
			$mov["x"] = 0;
			$mov["y"] = 0 - $dist;
			break;
		case 180:
			$mov["x"] = 0 - $dist;
			$mov["y"] = 0;
			break;
		case 270:
			$mov["x"] = 0;
			$mov["y"] = $dist;
			break;
		}
		$this->_position["x"] += $mov["x"];
		$this->_position["y"] += $mov["y"];
		// TODO Watch out for collisions
		$this->_mp -= $dist;
		$this->_untilTurn -= $dist;
	}

	private function rotate($toAngle)
	{
		if ($this->_untilTurn > 0)
			return ["error" => "Your ship cannot make turns that sharp! Try moving a little first."];
		$this->_angle = $toAngle;
		// TODO Rotation into obstacles
		// TODO Rotation into out of bounds
		// TODO Rotation into other ships (maybe considered bucaneering and the rotate is undone)
		$this->_untilTurn = $this->_handling;
	}

	public function move($orders)
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to move!"];
		if ($this->_phase != Ship::MOVE)
			return ["error" => "Your ship must be in the movement phase to move!"];
		if (!array_key_exists("type", $orders))
			return ["error" => "Your captain is drunk."];
		switch($orders["type"])
		{
		case "move":
			if (!array_key_exists("dist", $orders) or !is_numeric($orders["dist"]))
				return ["error" => "Must specify a distance to move."];
			if ($orders["dist"] > $this->_mp)
				return ["error" => "I'm giving her all she's got Captain!"];
			$this->move_forward($orders["dist"]);
			return TRUE;
		case "turn-left":
			return $this->rotate(($this->_angle + 90) % 360);
		case "turn-right":
			return $this->rotate(($this->_angle + 270) % 360);
		default:
			return ["error" => "'" . $orders["type"] . "' is not a valid movement type."];
		}
	}

	public function shoot()
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to shoot!"];
		if ($this->_phase != Ship::SHOOT)
			return ["error" => "Your ship must be in the shooting phase to shoot!"];
		return TRUE;
	}

	public function finishPhase()
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to do that!"];
		if ($this->_phase == Ship::SHOOT)
			$this->_status = Ship::DEACTIVE;
		else if ($this->_phase == Ship::MOVE)
			$this->_phase = Ship::SHOOT;
		else if ($this->_phase == Ship::ORDER)
		{
			$this->_phase = Ship::MOVE;
			$this->move_init();
		}
		return TRUE;
	}
}

?>
