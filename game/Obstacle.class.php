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

    public static function doc()
    {
        return file_get_contents("Obstacle.doc.txt");
    }

	public function getLocation()
	{
		return $this->_loc;
	}

	public function getSize()
	{
		return $this->_size;
	}

	public function toHTML()
	{
		$loc = $this->_loc;
		$size = $this->_size;
		echo "<div";
		echo " style=\"";
			echo "position: absolute;";
			echo "left: " . 100 * $loc["x"] / 150 . "%;";
			echo "top: " . 100 * $loc["y"] / 100 . "%;";
			echo "width: " . 100 * $size["x"] / 150 . "%;";
			echo "height: " . 100 * $size["y"] / 100 . "%;";
			echo "background-color: rgba(255, 64, 64, 0.5);";
		echo "\"";
		echo "></div>";
	}
}

?>
