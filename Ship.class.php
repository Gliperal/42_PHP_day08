<?php

abstract class Ship
{
	private $_player;
	private $_name;
	private $_size;
	private $_max_hp;
	private $_hp;
	private $_ep, $_pp;
	private $_cp; // Charge points (for weapons)
	private $_speed;
	private $_handling;
	private $_shield;
	private $_base_shield;
	private $_weapons;
	private $_speed_boost;
	private const DEACTIVE = 0, READY = 1, ACTIVE = 2;
	private $_status;
	private const ORDER = 0, MOVE = 1, SHOOT = 2;
	private $_phase;
	private $_stationary;

	public function __construct($name, $player)
	{
		$this->_name = $name;
		$this->_player = $player;
		$this->_status = Ship::DEACTIVE;
	}

	public function belongsTo($player)
	{
		return $this->_player == $player;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function isReady()
	{
		return $this->_status == Ship::READY;
	}

	public function isActive()
	{
		return $this->_status == Ship::ACTIVE;
	}

	public function ready()
	{
		$this->_status = Ship::READY;
	}

	public function activate()
	{
		$this->_status = Ship::ACTIVE;
		$this->_shield = $this->_base_shield;
		$this->_cp = 0;
		$this->_pp = $this->_ep;
		$this->_phase = Ship::ORDER;
	}

	private function repair($amount)
	{
	}

	public function order($orders)
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to give it orders!"];
		if ($this->_phase != Ship::ORDER)
			return ["error" => "Your ship must be in the order phase to give it orders!"];
		$total = 0;
		foreach (["speed", "shields", "weapons", "repairs"] as $key)
		{
			if (!array_key_exists($key, $orders))
				$orders[$key] = 0;
			$total += $orders[$key];
		}
		if ($total > $this->_pp)
			return ["error" => "Your ship does not have that many PP to spend!"];
		if ($orders["repairs"] > 0)
		{
			if ($this->_stationary)
				$this->repair($order["repairs"]);
			else
				return ["error" => "A ship must be stationary in order to enact repairs!"];
		}
		$this->_speed_boost = $orders["speed"]; // TODO Roll D6
		$this->_shield = $orders["shields"];
		$this->_cp = $orders["weapons"];
		$this->_pp -= $total;
		return TRUE;
	}

	public function move()
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to move!"];
		if ($this->_phase != Ship::MOVE)
			return ["error" => "Your ship must be in the movement phase to move!"];
		return TRUE;
	}

	public function shoot()
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to shoot!"];
		if ($this->_phase != Ship::SHOOT)
			return ["error" => "Your ship must be in the shooting phase to shoot!"];
		return TRUE;
	}

	public function finishPhase()
	{
		if ($this->_status != Ship::ACTIVE)
			return ["error" => "Your ship must be active to do that!"];
		if ($this->_phase == Ship::SHOOT)
			$this->_status = Ship::DEACTIVE;
		else
			$this->_phase++;
		return TRUE;
	}
}

?>
