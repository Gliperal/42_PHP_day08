<?php

class ImperialIronclad extends Ship
{
	private $_size = [2, 7];
	private $_max_hp = 8;
	private $_ep = 12;
	private $_speed = 12;
	private $_handling = 5;
	private $_base_shield = 2;
	private $_weapons = [];

	public function __construct($name, $player, $angle)
	{
		parent::__construct($name, $player, $angle);
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
