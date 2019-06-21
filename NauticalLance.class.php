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
		NauticalLance::moveOnAngle($this->_location, $this->_offset, $angle);
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
				echo "Target out of maximum range!" . PHP_EOL;
				return FALSE;
			}
			if ($distance < $this->_range[$r])
				break ;
			$r++;
		}
		echo "Rolling to hit at " . NauticalLance::RANGENAMES[$r] . " range ... ";
		$roll = rollD6();
		echo $roll;
		if ($roll >= $this->_rangeRolls[$r])
		{
			echo " hits!" . PHP_EOL;
			return TRUE;
		}
		else
		{
			echo " misses." . PHP_EOL;
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
				echo "Bullet went off map." . PHP_EOL;
				return;
			}
			foreach ($obstacles as $obstacle)
				if ($bullet->overlaps($obstacle))
				{
					echo "Bullet hit obstacle." . PHP_EOL;
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
