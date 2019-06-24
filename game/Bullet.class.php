<?php

include_once("Collidable.class.php");

class Bullet extends Collidable
{
	private $_location;
	private $_size;
	private $_step;

	public function __construct($location, $size, $angle)
	{
		$this->_location = $location;
		$this->_size = $size;
		switch($angle)
		{
		case 0:
			$this->_step = ["x" => 1, "y" => 0];
			break;
		case 90:
			$this->_step = ["x" => 0, "y" => -1];
			break;
		case 180:
			$this->_step = ["x" => -1, "y" => 0];
			break;
		case 270:
			$this->_step = ["x" => 0, "y" => 1];
			break;
		}
	}

	public static function doc()
    {
        return file_get_contents("Bullet.doc.txt");
	}

	protected function getLocation()
	{
		return $this->_location;
	}

	protected function getSize()
	{
		return $this->_size;
	}

	public function move()
	{
		$this->_location["x"] += $this->_step["x"];
		$this->_location["y"] += $this->_step["y"];
	}
}

?>
