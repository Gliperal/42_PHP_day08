<?php

include_once("Weapon.class.php");
include_once("Collidable.class.php");
include_once("Bullet.class.php");
include_once("MoveOnAngle.trait.php");
include_once("dice.php");

class NauticalLance extends Collidable implements Weapon
{
	use MoveOnAngle;

	private $_location;
	private $_offset;
	private $_angle;
	private $_angleOffset;
	private $_cp;
	// TODO These should probably be constants or something
	private $_charges = 0;
	private $_range = [31, 61, 91];
	private const RANGENAMES = ["short", "middle", "long"];
	private $_rangeRolls = [4, 5, 6];

	public function __construct($offset, $angleOffset)
	{
		$this->_offset = $offset;
		$this->_angleOffset = $angleOffset;
	}

	protected function getLocation()
	{
		return $this->_location;
	}

	protected function getSize()
	{
		return ["x" => 1, "y" => 1];
	}

	public function move($location, $angle)
	{
		$this->_angle = ($angle + $this->_angleOffset) % 360;
		$this->_location = $location;
		Console::log_debug("(" . $location["x"] . ", " . $location["y"] . ")");
		NauticalLance::moveOnAngle($this->_location, $this->_offset, $angle);
		Console::log_debug("(" . $this->_offset["x"] . ", " . $this->_offset["y"] . ")");
		Console::log_debug("NauticalLance moved to (" . $this->_location["x"] . ", " . $this->_location["y"] . ")");
	}

	public function resetCP()
	{
		$this->_cp = $this->_charges;
	}

	public function receiveCP($amount)
	{
		$this->_cp += $amount;
	}

	private function rollToHit($distance)
	{
		$r = 0;
		while (true)
		{
			if ($r == 3)
			{
				Console::log_message("Target out of maximum range!");
				return FALSE;
			}
			if ($distance < $this->_range[$r])
				break ;
			$r++;
		}
		$roll = rollD6();
		$msg = "Rolling to hit at " . NauticalLance::RANGENAMES[$r] . " range ... " . $roll;
		if ($roll >= $this->_rangeRolls[$r])
		{
			Console::log_message($msg . " hits!");
			return TRUE;
		}
		else
		{
			Console::log_message($msg . " misses.");
			return FALSE;
		}
	}

	public function shoot($ships, $obstacles)
	{
		$bullet_size = ["x" => 1, "y" => 1];
		$bullet = new Bullet($this->_location, $bullet_size, $this->_angle);
		while (true)
		{
			$bullet->move();
			if ($bullet->isOOB())
			{
				Console::log_debug("Bullet went off map.");
				return;
			}
			foreach ($obstacles as $obstacle)
				if ($bullet->overlaps($obstacle))
				{
					Console::log_debug("Bullet hit obstacle.");
					return;
				}
			foreach ($ships as $ship)
				if ($bullet->overlaps($ship))
				{
					// TODO Enfilade shot
					while ($this->_cp > 0)
					{
						$this->_cp--;
						if ($this->rollToHit($this->distanceTo($bullet)))
							$ship->takeDamage();
					}
					return;
				}
		}
	}
}
