<?php

interface Weapon
{
	public function move($location, $angle);
	public function shoot($ships, $obstacles);
	public function resetCP();
	public function receiveCP($amount);
}

?>
