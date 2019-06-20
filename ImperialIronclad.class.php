<?php

class ImperialIronclad extends Ship
{
	private $_size = [2, 7];
	private $_max_hp = 8;
	private $_hp = 8;
	private $_ep = 12;
	private $_speed = 10;
	private $_handling = 5;
	private $_base_shield = 2;
	private $_weapons = [];

	public function __construct($name, $player)
	{
		parent::__construct($name, $player);
	}
}

?>
