<?php

/*abstract*/ class Ship
{
	private $_player;
	private $_name;
	private $_size;
	private $_max_hp;
	private $_hp;
	private $_ep;
	private $_speed;
	private $_handling;
	private $_shield;
	private $_weapons;
	private const DEACTIVE = 0, READY = 1, ACTIVE = 2;
	private $_status;

	public function __construct($name, $player)
	{
		$this->_name = $name;
		$this->_player = $player;
		$this->_status = Ship::DEACTIVE;
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
	}

	public function move()
	{
	}
}

?>
