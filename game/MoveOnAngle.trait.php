<?php

trait MoveOnAngle
{
	private function moveOnAngle(&$location, $offset, $angle)
	{
		switch($angle)
		{
		case 0:
			$location["x"] = $location["x"] + $offset["x"];
			$location["y"] = $location["y"] + $offset["y"];
			break;
		case 90:
			$location["x"] += $offset["y"];
			$location["y"] -= $offset["x"];
			break;
		case 180:
			$location["x"] -= $offset["x"];
			$location["y"] -= $offset["y"];
			break;
		case 270:
			$location["x"] -= $offset["y"];
			$location["y"] += $offset["x"];
			break;
		}
	}
}

?>
