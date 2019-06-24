<?php

include_once("NauticalLance.class.php");

class ImperialScout extends Ship
{
	private $_size = ["x" => 4, "y" => 1];
	private $_center = ["x" => 1, "y" => 0];
	private $_max_hp = 4;
	private $_ep = 10;
	private $_speed = 20;
	private $_handling = 2;
	private $_base_shield = 0;
	private $_weapons;

	public function __construct($name, $player, $position, $angle)
	{
		parent::__construct($name, $player, $position, $angle);
		$this->_weapons = [
			new NauticalLance(["x" => 2, "y" => 0], 0)
		];
	}

    public static function doc()
    {
        return file_get_contents("ImperialScout.doc.txt");
    }

	protected function getRawSize()
	{
		return $this->_size;
	}

	protected function getCenter()
	{
		return $this->_center;
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
