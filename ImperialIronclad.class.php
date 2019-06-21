<?php

include_once("NauticalLance.class.php");

class ImperialIronclad extends Ship
{
	private $_size = ["x" => 2, "y" => 7];
	private $_max_hp = 8;
	private $_ep = 12;
	private $_speed = 12;
	private $_handling = 5;
	private $_base_shield = 2;
	private $_weapons;

	public function __construct($name, $player, $position, $angle)
	{
		parent::__construct($name, $player, $position, $angle);
		$this->_weapons = [
			new NauticalLance(["x" => 6, "y" => 0], 0),
			new NauticalLance(["x" => 6, "y" => 1], 0)
		];
	}

	protected function getSize()
	{
		return $this->_size;
	}

	protected function getMaxHP()
	{
		return $this->_max_hp;
	}

	protected function getEP()
	{
		return $this->_ep;
	}

	protected function getSpeed()
	{
		return $this->_speed;
	}

	protected function getHandling()
	{
		return $this->_handling;
	}

	protected function getBaseShield()
	{
		return $this->_base_shield;
	}

	protected function getWeapons()
	{
		return $this->_weapons;
	}
}

?>
