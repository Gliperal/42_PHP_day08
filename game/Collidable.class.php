<?php

abstract class Collidable
{
	protected abstract function getLocation();
	protected abstract function getSize();

	public function overlaps(Collidable $that)
	{
		$lA = $this->getLocation();
		$lB = $that->getLocation();
		$sA = $this->getSize();
		$sB = $that->getSize();
		return
			$lA["x"] + $sA["x"] > $lB["x"] and
			$lB["x"] + $sB["x"] > $lA["x"] and
			$lA["y"] + $sA["y"] > $lB["y"] and
			$lB["y"] + $sB["y"] > $lA["y"]
		;
	}

	public function isOOB()
	{
		$loc = $this->getLocation();
		$size = $this->getSize();
		return
			$loc["x"] < 0 or
			$loc["x"] + $size["x"] > 150 or
			$loc["y"] < 0 or
			$loc["y"] + $size["y"] > 100
		;
	}

	private function center()
	{
		$loc = $this->getLocation();
		$size = $this->getSize();
		$center = array();
		$center["x"] = $loc["x"] + $size["x"] / 2;
		$center["y"] = $loc["y"] + $size["y"] / 2;
		return $center;
	}

	private static function isLeftOf($start, $end, $point)
	{
		$vector1 = [
			"x" => $end["x"] - $start["x"],
			"y" => $end["y"] - $start["y"]
		];
		$vector2 = [
			"x" => $point["x"] - $start["x"],
			"y" => $point["y"] - $start["y"]
		];
		return ($vector1["x"] * $vector2["y"] - $vector1["y"] * $vector2["x"] < 0);
	}

	private function overlapsLine($start, $end)
	{
		$loc = $this->getLocation();
		$size = $this->getSize();
		$corners =
		[
			["x" => $loc["x"], "y" => $loc["y"]],
			["x" => $loc["x"], "y" => $loc["y"] + $size["y"]],
			["x" => $loc["x"] + $size["x"], "y" => $loc["y"]],
			["x" => $loc["x"] + $size["x"], "y" => $loc["y"] + $size["y"]]
		];
		$h = "";
		foreach ($corners as $corner)
		{
			if (Collidable::isLeftOf($start, $end, $corner))
			{
				if ($h == "right")
					return TRUE;
				$h = "left";
			}
			else
			{
				if ($h == "left")
					return TRUE;
				$h = "right";
			}
		}
		return FALSE;
	}

	public function hasLineOfSightTo(Collidable $that, $obstacles)
	{
		$cA = $this->center();
		$cB = $that->center();
		foreach ($obstacles as $obstacle)
			if ($obstacle->overlapsLine($cA, $cB))
				return FALSE;
		return TRUE;
	}

	public function distanceTo(Collidable $that)
	{
		$lA = $this->getLocation();
		$lB = $that->getLocation();
		$diffX = $lA["x"] - $lB["x"];
		$diffY = $lA["y"] - $lB["y"];
		return sqrt($diffX * $diffX + $diffY * $diffY);
	}
}

?>
