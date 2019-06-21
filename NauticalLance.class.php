<?php

include_once("Weapon.class.php");
include_once("Collidable.class.php");
include_once("Bullet.class.php");

class NauticalLance extends Collidable implements Weapon
{
	private $_location;
	private $_offset;
	private $_angle;
	private $_angleOffset;

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

	// TODO Put this in a trait
	private function moveOnAngle(&$location, $offset, $angle)
	{
		switch($angle)
		{
		case 0:
			$location["x"] = $location["x"] + $offset["x"];
			$location["y"] = $location["y"] + $offset["y"];
			break;
		case 90:
			$location["x"] += $offset["y"];
			$location["y"] -= $offset["x"];
			break;
		case 180:
			$location["x"] -= $offset["x"];
			$location["y"] -= $offset["y"];
			break;
		case 270:
			$location["x"] -= $offset["y"];
			$location["y"] += $offset["x"];
			break;
		}
	}

	public function move($location, $angle)
	{
		$this->_angle = ($angle + $this->_angleOffset) % 360;
		$this->_location = $location;
		NauticalLance::moveOnAngle($this->_location, $this->_offset, $angle);
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
					echo "Bullet hit obstacle:" . PHP_EOL;
					print_r($obstacle);
					return;
				}
			foreach ($ships as $ship)
				if ($bullet->overlaps($ship))
				{
					echo "Bullet hit ship:" . PHP_EOL;
					print_r($ship);
					// TODO Damage 'n shit
					return;
				}
		}
	}
}
