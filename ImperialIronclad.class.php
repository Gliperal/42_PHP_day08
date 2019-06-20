<?php

class ImperialIronclad extends Ship
{
	protected $_size = [2, 7];
	protected $_max_hp = 8;
	protected $_ep = 12;
	protected $_speed = 12;
	protected $_handling = 5;
	protected $_base_shield = 2;
	protected $_weapons = [];

	public function __construct($name, $player, $angle)
	{
		parent::__construct($name, $player, $angle);
	}
}

?>
