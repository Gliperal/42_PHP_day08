<?php

include_once("Collidable.class.php");

class Obstacle extends Collidable
{
	private $_loc;
	private $_size;

	public function __construct($lx, $ly, $sx, $sy)
	{
		$this->_loc = ["x" => $lx, "y" => $ly];
		$this->_size = ["x" => $sx, "y" => $sy];
	}

	public function __toString()
	{
		return sprintf("Obstacle[%d, %d, %d, %d]", $this->_loc["x"], $this->_loc["y"], $this->_size["x"], $this->_size["y"]);
	}

	public function getLocation()
	{
		return $this->_loc;
	}

	public function getSize()
	{
		return $this->_size;
	}
}

?>