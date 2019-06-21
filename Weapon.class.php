<?php

interface Weapon
{
	public function move($location, $angle);
	public function shoot($ships, $obstacles);
}

?>
