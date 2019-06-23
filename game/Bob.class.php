<?php

class Bob extends Ship
{
	private $_size = [3, 7];
	private $_max_hp = 1;
	protected $_ep = 12;
	protected $_speed = 6;
	protected $_handling = 1;
	protected $_base_shield = 25;
	protected $_weapons = [];

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
}

?>
